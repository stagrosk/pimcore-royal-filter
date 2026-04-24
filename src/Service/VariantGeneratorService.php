<?php

declare(strict_types=1);

namespace App\Service;

use App\Pimcore\Helpers\VersionHelper;
use App\Pimcore\Model\DataObject\RoyalFilter;
use App\Service\Generator\Mapper\FilterToProductMapper;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Fieldcollection\Data\ProductOption as ProductOptionFC;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\ProductOption;
use OpenDxp\Model\DataObject\ProductOptionGroup;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Model\DataObject\Whirlpool;

class VariantGeneratorService
{
    /**
     * @param FilterToProductMapper $filterToProductMapper
     * @param ProductMetadataService $productMetadataService
     * @param ProductOptionService $productOptionService
     */
    public function __construct(
        private readonly FilterToProductMapper  $filterToProductMapper,
        private readonly ProductMetadataService $productMetadataService,
        private readonly ProductOptionService   $productOptionService,
    ) {
    }

    /**
     * Process variants for a master product based on its source object
     *
     * @param Product $masterProduct
     *
     * @return void
     * @throws \Exception
     */
    public function processVariants(Product $masterProduct): void
    {
        $variantsData = [];

        // Get object from which product was generated
        $fromObject = $masterProduct->getGeneratedFromObject();

        if ($fromObject instanceof RoyalFilter) {
            // Single variant from RoyalFilter
            $variantsData[] = [
                'royalFilterSetup' => $fromObject,
                'masterProduct' => $masterProduct,
                'partOverrides' => [],
                'variantOptions' => [],
            ];
        } elseif ($fromObject instanceof Whirlpool) {
            // Multiple variants from Whirlpool setups
            $royalFilterSetups = $fromObject->getRoyalFilterSetups();

            if ($royalFilterSetups === null) {
                return;
            }

            /** @var \Pimcore\Model\DataObject\Fieldcollection\Data\RoyalFilterSetup $fieldCollection */
            foreach ($royalFilterSetups->getItems() as $fieldCollection) {
                $royalFilterSetup = $fieldCollection->getRoyalFilterSetup();

                if (!$royalFilterSetup instanceof RoyalFilter) {
                    continue;
                }

                $variantsData[] = [
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

        if (empty($variantsData)) {
            return;
        }

        // If we have more than 1 variant, calculate options from differences
        if (count($variantsData) > 1) {
            $variantsData = $this->resolveVariantOptions($variantsData);
        }

        // Create/update variant products
        foreach ($variantsData as $variantData) {
            $this->handleVariantProduct($variantData);
        }
    }

    /**
     * Resolve variant options by comparing classification store parameters
     *
     * @param array $variantsData
     *
     * @return array
     */
    private function resolveVariantOptions(array $variantsData): array
    {
        $mappedParametersOfParts = [];

        // Map parameters for each variant
        foreach ($variantsData as $index => $variantData) {
            // Create temporary product for metadata calculation
            $tempProduct = new Product();
            $this->productMetadataService->copyMetadata(
                $tempProduct,
                $variantData['royalFilterSetup'],
                $variantData['partOverrides'],
                true // skip pre-save
            );
            $mappedParametersOfParts[$index] = $this->productMetadataService->getMappedParametersIndexedByGroupAndKey($tempProduct);
        }

        // First setup is the base for comparison
        $baseParams = $mappedParametersOfParts[0];

        foreach ($mappedParametersOfParts as $index => $data) {
            // Skip first (base)
            if ($index === 0) {
                continue;
            }

            $differences = $this->findDifferencesRecursive($baseParams, $data);

            if (empty($differences)) {
                continue;
            }

            foreach ($differences as $keyPath => $values) {
                // Only accept BODY changes for variant options
                if (!str_contains(strtolower($keyPath), 'body')) {
                    continue;
                }

                // Format values
                $originalVal = $this->formatValue($values['original_value']);
                $newVal = $this->formatValue($values['new_value']);

                // Save on base setup if not exists
                if (!isset($variantsData[0]['variantOptions'][$keyPath])) {
                    $variantsData[0]['variantOptions'][$keyPath] = $originalVal;
                }

                // Save on current variant
                if (!isset($variantsData[$index]['variantOptions'][$keyPath])) {
                    $variantsData[$index]['variantOptions'][$keyPath] = $newVal;
                }
            }
        }

        return $variantsData;
    }

    /**
     * Handle creation/update of a single variant product
     *
     * @param array $variantData
     *
     * @return void
     * @throws \Exception
     */
    private function handleVariantProduct(array $variantData): void
    {
        /** @var RoyalFilter $royalFilterSetup */
        $royalFilterSetup = $variantData['royalFilterSetup'];
        /** @var Product $masterProduct */
        $masterProduct = $variantData['masterProduct'];

        // Try to find existing variant
        $variantProduct = $this->findExistingVariant($masterProduct, $royalFilterSetup);

        if (!$variantProduct instanceof Product) {
            $variantProduct = new Product();
        }

        // Map royal filter to product
        $this->filterToProductMapper->mapObjectToProduct(
            $variantProduct,
            $royalFilterSetup,
            ['extraData' => ['partOverrides' => $variantData['partOverrides']]]
        );

        // Set variant properties
        $variantProduct->setParent($masterProduct);
        $variantProduct->setType(AbstractObject::OBJECT_TYPE_VARIANT);
        $variantProduct->setSku(sprintf('V-%s', $royalFilterSetup->getId()));
        $variantProduct->setKey(Service::getValidKey(sprintf('V-%s', $royalFilterSetup->getKey()), 'object'));

        // Handle product options
        $this->assignProductOptions($variantProduct, $variantData['variantOptions']);

        // Save without versioning
        VersionHelper::useVersioning(function () use ($variantProduct) {
            $variantProduct->save();
        }, false);
    }

    /**
     * Find existing variant product by royal filter setup
     *
     * @param Product $masterProduct
     * @param RoyalFilter $royalFilterSetup
     *
     * @return Product|null
     */
    private function findExistingVariant(Product $masterProduct, RoyalFilter $royalFilterSetup): ?Product
    {
        $expectedSku = sprintf('V-%s', $royalFilterSetup->getId());

        foreach ($masterProduct->getChildren([AbstractObject::OBJECT_TYPE_VARIANT]) as $child) {
            if ($child instanceof Product && $child->getSku() === $expectedSku) {
                return $child;
            }
        }

        return null;
    }

    /**
     * Assign product options to variant
     *
     * @param Product $variantProduct
     * @param array $variantOptions
     *
     * @return void
     * @throws \Exception
     */
    private function assignProductOptions(Product $variantProduct, array $variantOptions): void
    {
        if (empty($variantOptions)) {
            $variantProduct->setProductOptions(null);
            return;
        }

        $fieldCollection = new Fieldcollection();

        foreach ($variantOptions as $optionKey => $optionValue) {
            // Parse option key (format: group_key)
            $parts = explode('_', $optionKey);
            $groupName = ucfirst($parts[0] ?? 'Unknown');
            $keyName = ucfirst($parts[1] ?? 'Option');

            // Generate codes
            $groupCode = $this->productOptionService->generateOptionGroupCode($groupName, $keyName);
            $optionCode = $this->productOptionService->generateOptionCode($groupCode, $optionValue);

            // Get or create option group
            $optionGroup = $this->productOptionService->getOrCreateOptionGroup(
                $groupCode,
                sprintf('%s %s', $groupName, $keyName)
            );

            // Get or create option
            $option = $this->productOptionService->getOrCreateOption(
                $optionCode,
                (string)$optionValue,
                $optionGroup
            );

            // Create fieldcollection item
            $productOptionFC = new ProductOptionFC();
            $productOptionFC->setProductOptionGroup($optionGroup);
            $productOptionFC->setProductOption($option);

            $fieldCollection->add($productOptionFC);
        }

        $variantProduct->setProductOptions($fieldCollection);
    }

    /**
     * Find differences between two arrays recursively
     *
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

    /**
     * Format value for storage
     *
     * @param mixed $value
     *
     * @return string
     */
    private function formatValue(mixed $value): string
    {
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return (string)$value;
    }
}