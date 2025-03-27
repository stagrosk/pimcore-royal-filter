<?php

namespace App\Service\Generator\Mapper;

use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;

interface MapperInterface
{
    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(Concrete $object, Product $product): Product;

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(Concrete $object, string $language): string;
}
