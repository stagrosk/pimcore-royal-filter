<?php

namespace App\Service\Generator\Mapper;

use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

interface MapperInterface
{
    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(AbstractObject $object, Product $product): Product;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\AbstractObject $product
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $object, AbstractObject $product, string $language): string;
}
