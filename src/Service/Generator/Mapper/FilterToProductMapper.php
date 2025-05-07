<?php

namespace App\Service\Generator\Mapper;

use App\Model\ClassificationStoreMappingItem;
use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Shopify\Model\Product\ProductStatusEnum;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Country;
use Pimcore\Model\DataObject\Data\Hotspotimage;
use Pimcore\Model\DataObject\Data\ImageGallery;
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
     */
    public function __construct(
        Translator $translator,
        ClassificationStoreHelper $classificationStoreHelper,
        ClassificationStoreService $classificationStoreService
    ) {
        parent::__construct($translator, $classificationStoreHelper, $classificationStoreService);
    }

    /**
     * @param \Pimcore\Model\DataObject\RoyalFilter $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(AbstractObject $object, Product $product): Product
    {
        // base
        $product->setPublished(true);
        $product->setEan(''); // TODO: ???
        $product->setSku(sprintf('RF-%s', $object->getId()));
        $product->setStatus(ProductStatusEnum::ACTIVE->value);
        $product->setIsVirtualProduct(false);
        $product->setIsGiftCard(false);
        $product->setProductType('filter');

        $product->setManufacturer($object->getManufacturer());
        $product->setGeneratedFromObject($object);

        // made in country
        $country = Country::getByName(self::COUNTRY_SLOVAKIA, 1);
        if ($country instanceof Country) {
            $product->setMadeIn($country->getIsoAlphaCode2());
        }

        // category
        $categoryPath = sprintf('/Shopify/Categories/AllProducts/%s', self::CATEGORY_FILTERS);
        $this->handleCategories($product, $categoryPath);

        // google taxonomy category
        $product->setTaxonomyCategory(self::SHOPIFY_GOOGLE_CATEGORY_POOL_SPA_FILTERS);

        // PRE-SAVE IN classification store helper! must be after key and parent assign
        // remap parameters and set them as new classification store values for product
        $this->copyMetadata($product, $object, true);

        // title (must be after metadata - dimensions resolved from classification store)/ description / seo
        foreach (Tool::getValidLanguages() as $language) {
            $product->setTitle($this->prepareTitle($object, $product, $language), $language);
            $product->setShortDescription($object->getShortDescription($language), $language);
            $product->setDescription($object->getDescription($language), $language);

            // TODO: finish
//            $product->setSeoTitle($object->getSeoTitle($language), $language);
//            $product->setSeoDescription($object->getSeoDescription($language), $language);
        }

        // images
        $product->setImageGallery($this->prepareImages($object));

        // prices
        $product->setPrices($object->getPrices());

        // pimcore base
        $path = sprintf('Shopify/Products/%s', self::CATEGORY_FILTERS);
        $product->setParent(Service::createFolderByPath($path));
        $product->setKey(Service::getValidKey(sprintf('RF-%s-%s', uniqid(), $product->getTitle()), 'object'));

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\RoyalFilter $object
     *
     * @return \Pimcore\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(AbstractObject $object): ImageGallery
    {
        $images = [];
        if ($object->getImage() instanceof Asset) {
            $images[] = new Hotspotimage($object->getImage());
        }

        // images
        $images = array_merge(
            $images,
            $object->getImageGallery()?->getItems(),
            $object->getBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getBody2()?->getImageGallery()?->getItems() ?? [],
            $object->getCenterBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getCenterBody2()?->getImageGallery()?->getItems() ?? [],
            $object->getEquipBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getEquipBody2()?->getImageGallery()?->getItems() ?? [],
        );

        return new ImageGallery($images);
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\AbstractObject $product
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $object, AbstractObject $product, string $language): string
    {
        $params = $this->getMappedParameters($product);

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
            $height = $mapping->findItemByKeyConfigName('height');
            $diameter = $mapping->findItemByKeyConfigName('diameter');
            $dimensions = sprintf('%s x ⌀%s', $height->getValue(), $diameter->getValue());

            // -> added center dimension to title
            $centerDiameterFrom = $mapping->findItemByKeyConfigName('centerDiameterFrom');
            if ($centerDiameterFrom instanceof ClassificationStoreMappingItem) {
                $centerDimensions = sprintf('⌀%s', $centerDiameterFrom->getValue());

                $centerDiameterTo = $mapping->findItemByKeyConfigName('centerDiameterTo');
                if ($centerDiameterTo instanceof ClassificationStoreMappingItem) {
                    $centerDimensions .= sprintf('->⌀%s', $centerDiameterTo->getValue());
                }

                $extraParams[] = $this->translator->trans('product_title_filter_center', [
                    '%dimensions%' => $centerDimensions
                ], 'messages', $language);
            }
        }

        return $this->translator->trans('product_title_filter', [
            '%dimensions%' => $dimensions,
            '%extraParams%' => implode(', ', $extraParams),
        ], 'messages', $language);
    }
}
