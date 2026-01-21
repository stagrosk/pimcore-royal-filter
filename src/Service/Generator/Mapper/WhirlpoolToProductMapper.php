<?php

namespace App\Service\Generator\Mapper;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Service\ProductMetadataService;
use App\Enum\ProductStatusEnum;
use Pimcore\Model\Asset;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Country;
use Pimcore\Model\DataObject\Data\Hotspotimage;
use Pimcore\Model\DataObject\Data\ImageGallery;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\Whirlpool;
use Pimcore\Tool;
use Pimcore\Translation\Translator;

class WhirlpoolToProductMapper extends BaseMapper
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
     * @param \Pimcore\Model\DataObject\AbstractObject|\Pimcore\Model\DataObject\Whirlpool $fromObject
     * @param array $extraData
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(Product $product, AbstractObject|Whirlpool $fromObject, array $extraData = []): Product
    {
        // base
        $product->setStatus(ProductStatusEnum::DRAFT->value);
        $product->setPublished(true);
        $product->setEan(''); // TODO: ???
        $product->setSku(sprintf('WRF-%s-%s', $fromObject->getId(), $product->getSku()));
        $product->setStatus(ProductStatusEnum::ACTIVE->value);
        $product->setIsVirtualProduct(false);
        $product->setIsGiftCard(false);
        $product->setProductType('whirlpoolFilter');
        $product->setManufacturer($fromObject->getManufacturer());
        $product->setGeneratedFromObject($fromObject);

        // made in country
        $country = Country::getByName(self::COUNTRY_SLOVAKIA, 1);
        if ($country instanceof Country) {
            $product->setMadeIn($country->getIsoAlphaCode2());
        }

        // collections
        $this->handleCollections($fromObject, $product);

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
        // todo: collect all images from all setups and merge them together, prevent duplicities
        $product->setImageGallery($this->prepareImages($product, $fromObject));

        // pimcore base
        $path = sprintf('Products/%s', $fromObject->getCollection()->getKey());
        $product->setParent(Service::createFolderByPath($path));
        $product->setKey(Service::getValidKey(sprintf('WRF-%s', str_replace(' ', '-', $product->getTitle())), 'object'));

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\Product $product
     * @param \Pimcore\Model\DataObject\Whirlpool $object
     *
     * @return \Pimcore\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(Product $product, AbstractObject $object): ImageGallery
    {
        $images = [];
        if ($object->getDefaultImage() instanceof Asset) {
            $images[] = new Hotspotimage($object->getDefaultImage());
        }

        // merge default image and image gallery
        $images = array_merge(
            $images,
            $object->getImages()?->getItems() ?? [],
            $product->getImageGallery()?->getItems() ?? []
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
        $codes = [];
        foreach ($fromObject->getPaperCartridges() as $cartridge) {
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
            '%title%' => $fromObject->getTitle($language),
            '%codes%' => implode(', ', $codes),
        ], 'messages', $language);
    }
}
