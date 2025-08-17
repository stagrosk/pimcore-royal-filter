<?php

namespace App\Service\Generator;

use App\Pimcore\Helpers\InheritanceHelper;
use App\Pimcore\Helpers\VersionHelper;
use App\Service\Generator\Mapper\WhirlpoolToProductMapper;
use Pimcore\Logger;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Whirlpool;
use Pimcore\Model\Document\Listing;

class WhirlpoolToProductGenerator extends BaseProductGenerator
{
    /**
     * @param \App\Service\Generator\Mapper\WhirlpoolToProductMapper $whirlpoolMapper
     */
    public function __construct(
        private readonly WhirlpoolToProductMapper $whirlpoolMapper,
    ) {
        $this->setClassName(Whirlpool::class);
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool $object
     *
     * @throws \Pimcore\Model\Element\DuplicateFullPathException
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
            $product = new Product();
        }

        // use inheritance
        InheritanceHelper::useInheritedValues(function () use ($object, $product) {
            // map data
            $this->whirlpoolMapper->mapObjectToProduct($product, $object);

            // save
            Logger::notice(sprintf('[WhirlpoolToProductGenerator] - Save product: %s', $product->getKey()));
            VersionHelper::useVersioning(function () use ($product) {
                $product->save();
            }, false);

            // save product on whirlpool
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
        $list->addConditionParam('royalFilterSetup__id IS NOT NULL');
    }
}
