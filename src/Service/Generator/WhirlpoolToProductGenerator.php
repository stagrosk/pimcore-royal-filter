<?php

namespace App\Service\Generator;

use App\Service\Generator\Mapper\WhirlpoolToProductMapper;
use Pimcore\Model\DataObject\Concrete;
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
     * @param \Pimcore\Model\DataObject\Concrete $object
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function generateProductForObject(Concrete $object): Product
    {
        $product =  new Product();
        $this->whirlpoolMapper->mapObjectToProduct($object, $product);

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
