<?php

namespace App\Service\Generator;

use App\Service\Generator\Mapper\FilterToProductMapper;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\RoyalFilter;
use Pimcore\Model\Document\Listing;

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
     * @param \Pimcore\Model\DataObject\Concrete $object
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function generateProductForObject(Concrete $object): Product
    {
        $product =  new Product();
        $this->filterMapper->mapObjectToProduct($object, $product);

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
