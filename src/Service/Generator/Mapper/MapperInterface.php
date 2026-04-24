<?php

namespace App\Service\Generator\Mapper;

use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Product;

interface MapperInterface
{
    /**
     * @param \OpenDxp\Model\DataObject\Product $product
     * @param \OpenDxp\Model\DataObject\AbstractObject $fromObject
     * @param array $extraData
     *
     * @return \OpenDxp\Model\DataObject\Product
     */
    public function mapObjectToProduct(Product $product, AbstractObject $fromObject, array $extraData = []): Product;

    /**
     * @param \OpenDxp\Model\DataObject\AbstractObject $product
     * @param \OpenDxp\Model\DataObject\AbstractObject $fromObject
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(AbstractObject $product, AbstractObject $fromObject, string $language): string;
}
