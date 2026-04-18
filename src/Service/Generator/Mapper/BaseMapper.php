<?php

namespace App\Service\Generator\Mapper;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Service\ProductMetadataService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Pimcore\Model\DataObject\Data\Hotspotimage;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductBenefitSet;
use Pimcore\Translation\Translator;

abstract class BaseMapper implements MapperInterface
{
    public const COUNTRY_CZECHIA = 'Czechia';
    public const COUNTRY_SLOVAKIA = 'Slovakia';
    public const BENEFIT_SET_FILTERS_PATH = '/ProductBenefitSets/ProsAndConsFilters';

    /**
     * @param \Pimcore\Translation\Translator $translator
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreHelper $classificationStoreHelper
     * @param \App\Pimcore\ClassificationStore\ClassificationStoreService $classificationStoreService
     * @param \App\Service\ProductMetadataService $productMetadataService
     */
    public function __construct(
        protected readonly Translator $translator,
        protected readonly ClassificationStoreHelper $classificationStoreHelper,
        protected readonly ClassificationStoreService $classificationStoreService,
        protected readonly ProductMetadataService $productMetadataService
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return void
     */
    public function handleCollections(AbstractObject $object, Product $product): void
    {
        $collection = $object->getCollection();
        $collections = [];
        while ($collection instanceof Collection) {
            $collections[] = $collection;
            $collection = $collection->getParent();
        }

        $product->setCollections($collections);
    }

    protected function assignBenefitSetForFilters(Product $product): void
    {
        $type = $product->getProductType();
        if ($type !== 'filter' && $type !== 'whirlpoolFilter') {
            return;
        }

        $benefitSet = ProductBenefitSet::getByPath(self::BENEFIT_SET_FILTERS_PATH);
        if ($benefitSet instanceof ProductBenefitSet) {
            $product->setBenefictSet($benefitSet);
        }
    }

    protected function deduplicateImages(array $hotspotImages): array
    {
        $seen = [];
        $images = [];
        foreach ($hotspotImages as $hotspotImage) {
            if (!$hotspotImage instanceof Hotspotimage || !$hotspotImage->getImage()) {
                continue;
            }
            $assetId = $hotspotImage->getImage()->getId();
            if (!isset($seen[$assetId])) {
                $seen[$assetId] = true;
                $images[] = $hotspotImage;
            }
        }
        return $images;
    }
}
