<?php

namespace App\Service\Generator\Mapper;

use App\Pimcore\ClassificationStore\ClassificationStoreHelper;
use App\Pimcore\ClassificationStore\ClassificationStoreService;
use App\Service\ProductMetadataService;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Collection;
use Pimcore\Model\DataObject\Product;
use Pimcore\Translation\Translator;

abstract class BaseMapper implements MapperInterface
{
    public const COUNTRY_CZECHIA = 'Czechia';
    public const COUNTRY_SLOVAKIA = 'Slovakia';

    public const SHOPIFY_GOOGLE_CATEGORY_POOL_SPA_FILTERS = 'gid://shopify/TaxonomyCategory/hg-18-1-3';

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
}
