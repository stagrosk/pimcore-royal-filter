<?php

namespace App\Service\Generator\Mapper;

use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Product;

interface MapperInterface
{
    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param \Pimcore\Model\DataObject\Product $product
     * @param bool $skipPreSave
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(AbstractObject $object, Product $product, bool $skipPreSave = false): Product;

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $object, string $language): string;
}
