<?php

namespace App\Service\Generator\Mapper;

use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

interface MapperInterface
{
    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\Product $product
     * @param bool $fromWhirlpool
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(AbstractObject $object, Product $product, bool $fromWhirlpool = false): Product;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\AbstractObject $product
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $object, AbstractObject $product, string $language): string;
}
