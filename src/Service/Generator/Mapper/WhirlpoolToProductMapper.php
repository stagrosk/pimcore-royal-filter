<?php

namespace App\Service\Generator\Mapper;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Tool;
use Pimcore\Translation\Translator;

class WhirlpoolToProductMapper extends BaseMapper
{
    /**
     * @param \Pimcore\Translation\Translator $translator
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreService $classificationStoreService
     * @param \App\Service\Generator\Mapper\FilterToProductMapper $filterToProductMapper
     */
    public function __construct(
        Translator $translator,
        ClassificationStoreHelper $classificationStoreHelper,
        ClassificationStoreService $classificationStoreService,
        private readonly FilterToProductMapper $filterToProductMapper
    ) {
        parent::__construct($translator, $classificationStoreHelper, $classificationStoreService);
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool $object
     * @param \Pimcore\Model\DataObject\Product $product
     * @param bool $skipPreSave
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(AbstractObject $object, Product $product, bool $skipPreSave = false): Product
    {
        // get filter setup
        $royalFilterSetup = $object->getRoyalFilterSetup();

        // map all from filter
        $this->filterToProductMapper->mapObjectToProduct($royalFilterSetup, $product, true);

        // base
        $product->setSku(sprintf('WRF-%s-%s', $object->getId(), $product->getSku()));

        // category
        $categoryPath = sprintf('/Shopify/Categories/AllProducts/%s', self::CATEGORY_FILTERS_BY_WHIRLPOOLS);
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
        $product->setImageGallery($this->prepareImages($object, $product));

        // pimcore base
        $path = sprintf('Shopify/Products/%s', self::CATEGORY_FILTERS_BY_WHIRLPOOLS);
        $product->setParent(Service::createFolderByPath($path));
        $product->setKey(Service::getValidKey(sprintf('WRF-%s-%s', uniqid(), $product->getTitle()), 'object'));

        // PRE-SAVE IN classification store helper! must be after key and parent assign
        // remap parameters and set them as new classification store values for product
        $this->copyMetadata($product, $object);

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return \Pimcore\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(AbstractObject $object, Product $product): ImageGallery
    {
        // images
        $images = array_merge(
            $object->getImages()?->getItems() ?? [],
            $product->getImageGallery()?->getItems() ?? [],
        );

        return new ImageGallery($images);
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool $object
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $object, string $language): string
    {
        $codes = [];
        foreach ($object->getPaperCartridges() as $cartridge) {
            /** @var \Pimcore\Model\DataObject\Fieldcollection\Data\PaperCartridgeCode $code */
            foreach ($cartridge->getCodes() as $code) {
                if ($code->getShowInTitle() === true) {
                    $codes[] = $code->getCode();
                }
            }
        }

        // unique
        $codes = array_unique($codes);

        return $this->translator->trans('product_title_whirlpool', [
            '%title%' => $object->getTitle($language),
            '%codes%' => implode(', ', $codes),
        ], 'messages', $language);
    }
}
