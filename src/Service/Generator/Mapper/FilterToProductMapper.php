<?php

namespace App\Service\Generator\Mapper;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Shopify\Model\Product\ShopifyProduct;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Classificationstore;
use Pimcore\Model\DataObject\Country;
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
     * @param bool $skipPreSave
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(AbstractObject $object, Product $product, bool $skipPreSave = false): Product
    {
        // base
        $product->setPublished(true);
        $product->setEan(''); // TODO: ???
        $product->setSku(sprintf('RF-%s', $object->getId()));
        $product->setStatus(ShopifyProduct::STATUS_ACTIVE);
        $product->setIsVirtualProduct(false);
        $product->setIsGiftCard(false);

        $product->setManufacturer($object->getManufacturer());

        $country = Country::getByName(self::COUNTRY_SLOVAKIA, 1);
        if ($country instanceof Country) {
            $product->setMadeIn($country->getIsoAlphaCode2());
        }

        // category
        $categoryPath = sprintf('/Shopify/Categories/AllProducts/%s', self::CATEGORY_FILTERS);
        $this->handleCategories($product, $categoryPath);


        // title / description / seo
        foreach (Tool::getValidLanguages() as $language) {
            $product->setTitle($this->prepareTitle($object, $language), $language);
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

        // PRE-SAVE IN classification store helper! must be after key and parent assign
        // remap parameters and set them as new classification store values for product
        $this->copyMetadata($product, $object, $skipPreSave);

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\RoyalFilter $object
     *
     * @return \Pimcore\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(AbstractObject $object): ImageGallery
    {
        // images
        $images = array_merge(
            $object->getImageGallery()?->getItems() ?? [],
            $object->getEquipBody1()?->getImageGallery()?->getItems() ?? [],
            $object->getEquipBody2()?->getImageGallery()?->getItems() ?? []
        );

        return new ImageGallery($images);
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $object, string $language): string
    {
        $params = $this->getMappedParameters($object);

        $codes = [];
        // TODO: nacitat vsetky dependencies kde je filter pouzity na virivke a z virivky nacitat kody
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
        if (isset($params['body1']) ) {
            // not set body2 -> get directly height
            if (!isset($params['body2']) ) {
                $dimensions .= $params['body1']['mappedValues']['height']['value'];
            } else {
                $heightParam1 = $params['body1']['mappedValues']['height'];
                $value1 = (int)$heightParam1['rawValue'];
                $value2 = (int)$params['body2']['mappedValues']['height']['rawValue'];

                $unit = '';
                if (!empty($heightParam1['unit'])) {
                    $unit = $heightParam1['unit'];
                }

                $dimensions = sprintf('%s %s', $value1 + $value2, $unit);
            }

            // add diameter
            $dimensions = sprintf('%s x %s', $dimensions, $params['body1']['mappedValues']['diameter']['value']);
        }

        return $this->translator->trans('product_title_filter', [
            '%dimensions%' => $dimensions,
            '%codes%' => implode(', ', $codes),
        ]);
    }
}
