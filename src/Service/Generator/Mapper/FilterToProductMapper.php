<?php

namespace App\Service\Generator\Mapper;

use App\OpenDxp\Model\ClassificationStore\ClassificationStoreMappingItem;
use App\OpenDxp\ClassificationStore\ClassificationStoreHelper;
use App\OpenDxp\ClassificationStore\ClassificationStoreService;
use App\OpenDxp\Model\DataObject\RoyalFilter;
use App\Service\Generator\ProductFolderResolver;
use App\Service\ProductMetadataService;
use App\Enum\ProductStatusEnum;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Adapter;
use OpenDxp\Model\DataObject\Country;
use OpenDxp\Model\DataObject\Data\ImageGallery;
use OpenDxp\Model\DataObject\Folder;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Service;
use OpenDxp\Tool;
use OpenDxp\Translation\Translator;

class FilterToProductMapper extends BaseMapper
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
     * @param \OpenDxp\Model\DataObject\AbstractObject|\App\OpenDxp\Model\DataObject\RoyalFilter $fromObject
     * @param array $extraData
     *
     * @throws \OpenDxp\Model\Element\DuplicateFullPathException
     * @return \OpenDxp\Model\DataObject\Product
     */
    public function mapObjectToProduct(Product $product, AbstractObject|RoyalFilter $fromObject, array $extraData = []): Product
    {
        // base
        $product->setPublished(true);
        $product->setEan('');
        $product->setSku(sprintf('RF-%s', $fromObject->getId()));
        $product->setStatus(ProductStatusEnum::ACTIVE->value);
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

        // benefit set
        $this->assignBenefitSetForFilters($product);

        // images
        $product->setImageGallery($this->prepareImages($fromObject));

        // prices
        $product->setPrices($fromObject->getPrices());

        // pimcore base - mirror source object hierarchy under Products root
        $product->setParent($this->productFolderResolver->resolveParentFolder($fromObject, ProductFolderResolver::PRODUCTS_ROOT_FILTER));
        $product->setKey(Service::getValidKey(sprintf('RF-%s', str_replace(' ', '-', $product->getTitle())), 'object'));

        return $product;
    }

    /**
     * @param \OpenDxp\Model\DataObject\AbstractObject|\App\OpenDxp\Model\DataObject\RoyalFilter $object
     *
     * @return \OpenDxp\Model\DataObject\Data\ImageGallery
     */
    private function prepareImages(AbstractObject|RoyalFilter $object): ImageGallery
    {
        $allImages = array_merge(
            $object->getImageGallery()?->getItems() ?? [],
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
        // body/center dimensions hidden from the title - they are visible directly on the product detail
        // and were producing very long names. Adapter name is used as the differentiator instead.
//        $params = $this->productMetadataService->getMappedParametersOfParts($product);
//
//        $dimensions = '';
//        $extraParams = [];
//
//        if (!empty($params)) {
//            /** @var \App\OpenDxp\Model\ClassificationStore\ClassificationStoreMapping $mapping */
//            $mapping = reset($params)['mapping'];
//
//            $height = $mapping->findItemByKeyConfigName('body', 'height');
//            $diameter = $mapping->findItemByKeyConfigName('body', 'diameter');
//            if ($height instanceof ClassificationStoreMappingItem && $diameter instanceof ClassificationStoreMappingItem) {
//                $dimensions = sprintf('%s x ⌀%s', $height->getValue(), $diameter->getValue());
//            }
//
//            $centerDiameterFrom = $mapping->findItemByKeyConfigName('center', 'centerDiameterFrom');
//            if ($centerDiameterFrom instanceof ClassificationStoreMappingItem) {
//                $centerDimensions = sprintf('⌀%s', $centerDiameterFrom->getValue());
//
//                $centerDiameterTo = $mapping->findItemByKeyConfigName('center', 'centerDiameterTo');
//                if ($centerDiameterTo instanceof ClassificationStoreMappingItem
//                    && $centerDiameterFrom->getValue() !== $centerDiameterTo->getValue()
//                ) {
//                    $centerDimensions .= sprintf('->⌀%s', $centerDiameterTo->getValue());
//                }
//
//                $extraParams[] = trim($this->translator->trans('product_title_filter_center', [
//                    '%dimensions%' => $centerDimensions
//                ], 'messages', $language), " ()");
//            }
//        }

        $title = $fromObject->getTitle($language);

        $adapter = $fromObject instanceof RoyalFilter ? $fromObject->getAdapter() : null;
        if ($adapter instanceof Adapter) {
            $adapterTitle = $adapter->getTitle($language);
            if (!empty($adapterTitle)) {
                $title .= ' (' . $adapterTitle . ')';
            }
        }

        return $title;
    }
}
