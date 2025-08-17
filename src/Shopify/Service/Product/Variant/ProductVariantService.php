<?php

namespace App\Shopify\Service\Product\Variant;

use App\Pimcore\Helpers\VersionHelper;
use App\Pimcore\Model\DataObject\RoyalFilter;
use App\Service\Generator\Mapper\FilterToProductMapper;
use App\Service\Generator\Mapper\WhirlpoolToProductMapper;
use App\Service\ProductMetadataService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Fieldcollection;
use Pimcore\Model\DataObject\Fieldcollection\Data\VariantOption;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\ShopifyMetafieldDefinition;
use Pimcore\Model\DataObject\Whirlpool;

readonly class ProductVariantService
{
    /**
     * @param \App\Service\Generator\Mapper\FilterToProductMapper $filterToProductMapper
     * @param \App\Service\ProductMetadataService $productMetadataService
     */
    public function __construct(
        private FilterToProductMapper  $filterToProductMapper,
        private ProductMetadataService $productMetadataService,
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\Product $masterProduct
     * @param array $shopifyVariantsData
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
     * @throws \Exception
     * @return void
     */
    public function processVariants(Product $masterProduct, array $shopifyVariantsData = []): void
    {
        $variantsData = [];

        // get object from which was generated
        $fromObject = $masterProduct->getGeneratedFromObject();
        if ($fromObject instanceof RoyalFilter) {
            $variantsData[] = [
                'apiId' => $shopifyVariantsData[0]['node']['id'],
                'royalFilterSetup' => $fromObject,
                'masterProduct' => $masterProduct,
                'partOverrides' => [],
                'variantOptions' => [],
            ];
        } else if ($fromObject instanceof Whirlpool) {
            /** @var \Pimcore\Model\DataObject\Fieldcollection\Data\RoyalFilterSetup $fieldCollection */
            foreach ($fromObject->getRoyalFilterSetups()?->getItems() as $fieldCollection) {
                $royalFilterSetup = $fieldCollection->getRoyalFilterSetup();
                $variantsData[] = [
                    'apiId' => isset($shopifyVariantsData[$fieldCollection->getIndex()]) ? $shopifyVariantsData[$fieldCollection->getIndex()]['node']['id'] : null,
                    'royalFilterSetup' => $royalFilterSetup,
                    'masterProduct' => $masterProduct,
                    'partOverrides' => [
                        'adapter' => $fieldCollection->getAdapter(),
                        'equipBody1' => $fieldCollection->getEquipBody1(),
                        'equipBody2' => $fieldCollection->getEquipBody2(),
                    ],
                    'variantOptions' => [],
                ];
            }
        }

        // we have more than 1 variant, we need to handle options
        if (count($variantsData) > 1) {
            $mappedParametersOfParts = [];
            foreach ($variantsData as $index => $variantData) {
                // temporary product, because of metafields calculation
                $product = new Product();
                $this->productMetadataService->copyMetadata($product, $variantData['royalFilterSetup'], $variantData['partOverrides'], true); // DON`T save!
                $mappedParametersOfParts[$index] = $this->productMetadataService->getMappedParametersIndexedByGroupAndKey($product);
            }

            $variantsData = $this->resolveOptions($variantsData, $mappedParametersOfParts);
        }

        foreach ($variantsData as $variantData) {
            $this->handleVariantProduct($variantData);
        }
    }

    /**
     * @param array $variantData
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
     * @return void
     */
    private function handleVariantProduct(
        array $variantData
    ): void {
        $variantProduct = null;

        // we have api id -> try to get
        if ($variantData['apiId'] !== null) {
            $variantProduct = Product::getByApiId($variantData['apiId'], 1);
        }

        // not found -> create
        if (!$variantProduct instanceof Product) {
            // try to get first master product child -> as variantProduct
            $variantProduct = new Product();
        }

        // map royal filter to product
        $this->filterToProductMapper->mapObjectToProduct($variantProduct, $variantData['royalFilterSetup'], ['extraData' => ['partOverrides' => $variantData['partOverrides']]]);

        // set properties belong to variantProduct
        $variantProduct->setApiId($variantData['apiId']);
        $variantProduct->setParent($variantData['masterProduct']);
        $variantProduct->setType(AbstractObject::OBJECT_TYPE_VARIANT);
        $variantProduct->setSku(sprintf('V-%s', $variantData['royalFilterSetup']->getId()));
        $variantProduct->setKey(Service::getValidKey(sprintf('V-%s', $variantData['royalFilterSetup']->getKey()), 'object'));

        // options
        $variantOptionsFieldCollection = null;
        if (count($variantData['variantOptions']) > 0) {
            $variantOptionsFieldCollection = new Fieldcollection();
            foreach ($variantData['variantOptions'] ?? [] as $variantOptionKey => $variantOptionValue) {
                // find option
                $shopifyMetaDefinition = ShopifyMetafieldDefinition::getByMetaKey(sprintf('product_%s', $variantOptionKey), 1);
                if ($shopifyMetaDefinition instanceof ShopifyMetafieldDefinition) {
                    $variantOption = new VariantOption();
                    $variantOption->setShopifyMetafieldDefinition($shopifyMetaDefinition);
                    $variantOption->setOptionValue($variantOptionValue);

                    // add to fieldcollection
                    $variantOptionsFieldCollection->add($variantOption);
                }
            }
        }
        $variantProduct->setVariantOptions($variantOptionsFieldCollection);

        // save
        VersionHelper::useVersioning(function () use ($variantProduct) {
            $variantProduct->save();
        }, false);
    }

    /**
     * @param array $variantsData
     * @param array $mappedParameters
     *
     * @return array
     */
    private function resolveOptions(array $variantsData, array $mappedParameters): array
    {
        // first setup that is as main, all others are for comparison
        $baseParams = $mappedParameters[0];
        foreach ($mappedParameters as $index => $data) {
            // skip first
            if ($index === 0) {
                continue;
            }

            $differencesInCurrentSet = $this->findDifferencesRecursive($baseParams, $data);
            if (!empty($differencesInCurrentSet)) {
                foreach ($differencesInCurrentSet as $keyPath => $values) {
                    // accept only BODY changes
                    if (!str_contains($keyPath, 'body')) {
                        continue;
                    }

                    $originalVal = is_array($values['original_value']) || is_object($values['original_value']) ? json_encode($values['original_value']) : $values['original_value'];
                    // save only on base setup if not exists
                    if (!isset($variantsData[0]['variantOptions'][$keyPath])) {
                        $variantsData[0]['variantOptions'][$keyPath] = $originalVal;
                    }

                    $newVal = is_array($values['new_value']) || is_object($values['new_value']) ? json_encode($values['new_value']) : $values['new_value'];
                    if (!in_array($keyPath, $variantsData[$index]['variantOptions'], true)) {
                        $variantsData[$index]['variantOptions'][$keyPath] = $newVal;
                    }
                }
            }
        }

        return $variantsData;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @param string $path
     *
     * @return array
     */
    private function findDifferencesRecursive(array $array1, array $array2, string $path = ''): array
    {
        $differences = [];

        $allKeys = array_unique(array_merge(array_keys($array1), array_keys($array2)));

        foreach ($allKeys as $key) {
            $currentPath = $path ? $path . '_' . $key : $key;

            $value1 = $array1[$key] ?? null;
            $value2 = $array2[$key] ?? null;

            if (is_array($value1) && is_array($value2)) {
                $nestedDifferences = $this->findDifferencesRecursive($value1, $value2, $currentPath);
                $differences = array_merge($differences, $nestedDifferences);
            } elseif ($value1 !== $value2) {
                $differences[$currentPath] = [
                    'original_value' => $value1,
                    'new_value' => $value2,
                ];
            }
        }

        return $differences;
    }
}
