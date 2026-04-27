<?php

namespace App\Service\Generator\Mapper;

use App\OpenDxp\ClassificationStore\ClassificationStoreHelper;
use App\OpenDxp\ClassificationStore\ClassificationStoreService;
use App\Service\Generator\ProductFolderResolver;
use App\Service\ProductMetadataService;
use App\Enum\ProductStatusEnum;
use OpenDxp\Model\Asset;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Country;
use OpenDxp\Model\DataObject\Data\Hotspotimage;
use OpenDxp\Model\DataObject\Data\ImageGallery;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Model\DataObject\Whirlpool;
use OpenDxp\Tool;
use OpenDxp\Translation\Translator;

class WhirlpoolToProductMapper extends BaseMapper
{
    /**
     * @param \OpenDxp\Translation\Translator $translator
     * @param \App\OpenDxp\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\OpenDxp\ClassificationStore\ClassificationStoreService $classificationStoreService
     * @param \App\Service\ProductMetadataService $productMetadataService
     * @param \App\Service\Generator\ProductFolderResolver $productFolderResolver
     */
    public function __construct(
        Translator $translator,
        ClassificationStoreHelper $classificationStoreHelper,
        ClassificationStoreService $classificationStoreService,
        ProductMetadataService $productMetadataService,
        ProductFolderResolver $productFolderResolver,
    ) {
        parent::__construct(
            $translator,
            $classificationStoreHelper,
            $classificationStoreService,
            $productMetadataService,
            $productFolderResolver,
        );
    }

    /**
     * @param \OpenDxp\Model\DataObject\Product $product
     * @param \OpenDxp\Model\DataObject\AbstractObject|\OpenDxp\Model\DataObject\Whirlpool $fromObject
     * @param array $extraData
     *
     * @throws \Exception
     * @return \OpenDxp\Model\DataObject\Product
     */
    public function mapObjectToProduct(Product $product, AbstractObject|Whirlpool $fromObject, array $extraData = []): Product
    {
        // base
        $product->setPublished(true);
        $product->setEan('');
        $product->setSku(sprintf('WRF-%s-%s', $fromObject->getId(), $product->getSku()));
        $product->setStatus(ProductStatusEnum::ACTIVE->value);
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
        // skip title generation per language when operator locked it via disableTitleGenerator
        $generateTitle = $product->getDisableTitleGenerator() !== true;
        foreach (Tool::getValidLanguages() as $language) {
            if ($generateTitle) {
                $product->setTitle($this->prepareTitle($product, $fromObject, $language), $language);
            }
            $product->setShortDescription($fromObject->getShortDescription($language), $language);
            $product->setDescription($fromObject->getDescription($language), $language);
//            $product->setSeoTitle($product->getTitle($language), $language);
//            $product->setSeoDescription($product->getDescription($language), $language);
        }

        // paper cartridges
        $product->setPaperCartridges($fromObject->getPaperCartridges());

        // benefit set
        $this->assignBenefitSetForFilters($product);

        // images
        $product->setImageGallery($this->prepareImages($product, $fromObject));

        // pimcore base - mirror source object hierarchy under whirlpool products root
        $product->setParent($this->productFolderResolver->resolveParentFolder($fromObject, ProductFolderResolver::PRODUCTS_ROOT_WHIRLPOOL));
        $product->setKey(Service::getValidKey(sprintf('WRF-%s', str_replace(' ', '-', $product->getTitle())), 'object'));

        return $product;
    }

    /**
     * @param \OpenDxp\Model\DataObject\Product $product
     * @param \OpenDxp\Model\DataObject\Whirlpool $object
     *
     * @return \OpenDxp\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(Product $product, AbstractObject $object): ImageGallery
    {
        $allImages = [];
        if ($object->getDefaultImage() instanceof Asset) {
            $allImages[] = new Hotspotimage($object->getDefaultImage());
        }

        $allImages = array_merge(
            $allImages,
            $object->getImages()?->getItems() ?? [],
            $product->getImageGallery()?->getItems() ?? []
        );

        return new ImageGallery($this->deduplicateImages($allImages));
    }

    /**
     * @param \OpenDxp\Model\DataObject\AbstractObject $product
     * @param \OpenDxp\Model\DataObject\AbstractObject $fromObject
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $product, AbstractObject $fromObject, string $language): string
    {
        $codes = [];
        foreach ($fromObject->getPaperCartridges() as $cartridge) {
            /** @var \OpenDxp\Model\DataObject\Fieldcollection\Data\PaperCartridgeCode $code */
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
