<?php

namespace App\Service\Generator;

use App\Pimcore\Helpers\InheritanceHelper;
use App\Pimcore\Helpers\VersionHelper;
use App\Service\Generator\Mapper\FilterToProductMapper;
use OpenDxp\Logger;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\RoyalFilter;
use OpenDxp\Model\Document\Listing;

class FilterToProductGenerator extends BaseProductGenerator
{
    /**
     * @param \App\Service\Generator\Mapper\FilterToProductMapper $filterMapper
     */
    public function __construct(
        private readonly FilterToProductMapper    $filterMapper,
    ) {
        $this->setClassName(RoyalFilter::class);
    }

    /**
     * @param \Pimcore\Model\DataObject\RoyalFilter $object
     *
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\Product
     */
    public function generateProductForObject(AbstractObject $object): Product
    {
        // get product
        $product = $object->getProduct();

        // if the product was not already generated -> create new
        if (!$product instanceof Product) {
            // try to get filter by relation -> generatedFromObject
            $product = Product::getByGeneratedFromObject($object, 1);
            $product?->delete();

            // create new
            $product =  new Product();
        }

        // use inheritance
        InheritanceHelper::useInheritedValues(function () use ($object, $product) {
            // map data
            $this->filterMapper->mapObjectToProduct($product, $object);

            // save
            Logger::notice(sprintf('[FilterToProductGenerator] - Save product: %s', $product->getKey()));
            VersionHelper::useVersioning(function () use ($product) {
                $product->save();
            }, false);

            // save product on object
            $object->setProduct($product);
            VersionHelper::useVersioning(function () use ($object) {
                $object->save();
            }, false);
        });

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\Listing|\Pimcore\Model\Document\Listing $list
     */
    public function prepareListConditions(\Pimcore\Model\DataObject\Listing|Listing $list): void
    {
        $list->setUnpublished(false);
        $list->addConditionParam('generateAsProduct IS TRUE');
        $list->addConditionParam('product__id IS NULL');
    }
}
