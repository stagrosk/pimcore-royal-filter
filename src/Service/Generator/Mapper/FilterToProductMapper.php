<?php

namespace App\Service\Generator\Mapper;

use App\Model\ClassificationStoreMappingItem;
use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Pimcore\Model\DataObject\RoyalFilter;
use App\Service\ProductMetadataService;
use App\Shopify\Model\Product\ProductStatusEnum;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Country;
use Pimcore\Model\DataObject\Data\Hotspotimage;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Folder;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Tool;
use Pimcore\Translation\Translator;

class FilterToProductMapper extends BaseMapper
{
    /**
     * @param \Pimcore\Translation\Translator $translator
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreService $classificationStoreService
     * @param \App\Service\ProductMetadataService $productMetadataService
     */
    public function __construct(
        Translator $translator,
        ClassificationStoreHelper $classificationStoreHelper,
        ClassificationStoreService $classificationStoreService,
        ProductMetadataService $productMetadataService
    ) {
        parent::__construct(
            $translator,
            $classificationStoreHelper,
            $classificationStoreService,
            $productMetadataService
        );
    }

    /**
     * @param \Pimcore\Model\DataObject\Product $product
     * @param \Pimcore\Model\DataObject\AbstractObject|\App\Pimcore\Model\DataObject\RoyalFilter $fromObject
     * @param array $extraData
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(Product $product, AbstractObject|RoyalFilter $fromObject, array $extraData = []): Product
    {
        // base
        $product->setPublished(true);
        $product->setEan(''); // TODO: ???
        $product->setSku(sprintf('RF-%s', $fromObject->getId()));
        $product->setStatus(ProductStatusEnum::ACTIVE->value);
        $product->setIsVirtualProduct(false);
        $product->setIsGiftCard(false);
        $product->setProductType('filter');
        $product->setManufacturer($fromObject->getManufacturer());
        $product->setGeneratedFromObject($fromObject);

        // made in country
        $country = Country::getByName(self::COUNTRY_SLOVAKIA, 1);
        if ($country instanceof Country) {
            $product->setMadeIn($country->getIsoAlphaCode2());
        }

        // collections
        $this->handleCollections($fromObject, $product);

        // google taxonomy category
        $product->setTaxonomyCategory(self::SHOPIFY_GOOGLE_CATEGORY_POOL_SPA_FILTERS);

        // PRE-SAVE IN classification store helper! must be after key and parent assign
        // remap parameters and set them as new classification store values for product
        $this->productMetadataService->copyMetadata($product, $fromObject, $extraData['partOverrides'] ?? [], true);

        // title (must be after metadata - dimensions resolved from classification store)/ description / seo
        foreach (Tool::getValidLanguages() as $language) {
            $product->setTitle($this->prepareTitle($product, $fromObject, $language), $language);
            $product->setShortDescription($fromObject->getShortDescription($language), $language);
            $product->setDescription($fromObject->getDescription($language), $language);
//            $product->setSeoTitle($product->getTitle($language), $language);
//            $product->setSeoDescription($product->getDescription($language), $language);
        }

        // images
        $product->setImageGallery($this->prepareImages($fromObject));

        // prices
        $product->setPrices($fromObject->getPrices());

        // pimcore base
        $path = sprintf('Shopify/Products/%s', $fromObject->getCollection()->getKey());
        $product->setParent(Service::createFolderByPath($path));
        $product->setKey(Service::getValidKey(sprintf('RF-%s', str_replace(' ', '-', $product->getTitle())), 'object'));

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|\App\Pimcore\Model\DataObject\RoyalFilter $object
     *
     * @return \Pimcore\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(AbstractObject|RoyalFilter $object): ImageGallery
    {
        // images
        $images = array_merge(
            $object->getImageGallery()?->getItems(),
            $object->getBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getBodyMiddle()?->getImageGallery()?->getItems() ?? [],
            $object->getBody2()?->getImageGallery()?->getItems() ?? [],
            $object->getCenterBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getCenterBodyMiddle()?->getImageGallery()?->getItems() ?? [],
            $object->getCenterBody2()?->getImageGallery()?->getItems() ?? [],
            $object->getAdapter()?->getImageGallery()?->getItems() ?? [],
            $object->getEquipBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getEquipBody2()?->getImageGallery()?->getItems() ?? [],
        );

        return new ImageGallery($images);
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $product
     * @param \Pimcore\Model\DataObject\AbstractObject $fromObject
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $product, AbstractObject $fromObject, string $language): string
    {
        $params = $this->productMetadataService->getMappedParametersOfParts($product);

        // TODO: nacitat vsetky dependencies kde je filter pouzity na virivke a z virivky nacitat kody
//        $codes = [];
//        foreach ($object->getPaperCartridges() as $cartridge) {
//            foreach ($cartridge->getCodes() as $code) {
//                if ($code->getShowInTitle() === true) {
//                    $codes[] = $code;
//                }
//            }
//        }
//        // unique
//        $codes = array_unique($codes);

        $dimensions = '';
        $extraParams = [];

        if (!empty($params)) {
            // get first mapping
            /** @var \App\Model\ClassificationStoreMapping $mapping */
            $mapping = reset($params)['mapping'];

            // add diameter
            $height = $mapping->findItemByKeyConfigName('body', 'height');
            $diameter = $mapping->findItemByKeyConfigName('body', 'diameter');
            $dimensions = sprintf('%s x ⌀%s', $height->getValue(), $diameter->getValue());

            // -> added center dimension to title
            $centerDiameterFrom = $mapping->findItemByKeyConfigName('center', 'centerDiameterFrom');
            if ($centerDiameterFrom instanceof ClassificationStoreMappingItem) {
                $centerDimensions = sprintf('⌀%s', $centerDiameterFrom->getValue());

                $centerDiameterTo = $mapping->findItemByKeyConfigName('center', 'centerDiameterTo');
                if ($centerDiameterTo instanceof ClassificationStoreMappingItem
                    && $centerDiameterFrom->getValue() !== $centerDiameterTo->getValue()
                ) {
                    $centerDimensions .= sprintf('->⌀%s', $centerDiameterTo->getValue());
                }

                $extraParams[] = $this->translator->trans('product_title_filter_center', [
                    '%dimensions%' => $centerDimensions
                ], 'messages', $language);
            }
        }

        return $this->translator->trans('product_title_filter', [
            '%title%' => $fromObject->getTitle($language),
            '%dimensions%' => $dimensions,
            '%extraParams%' => implode(', ', $extraParams),
        ], 'messages', $language);
    }
}
