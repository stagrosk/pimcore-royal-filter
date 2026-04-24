<?php

namespace App\Service\Generator;

use App\OpenDxp\Helpers\InheritanceHelper;
use App\OpenDxp\Helpers\VersionHelper;
use App\Service\Generator\Mapper\WhirlpoolToProductMapper;
use App\Service\VariantGeneratorService;
use OpenDxp\Logger;
use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Product;
use OpenDxp\Model\DataObject\Whirlpool;
use OpenDxp\Model\Document\Listing;

class WhirlpoolToProductGenerator extends BaseProductGenerator
{
    /**
     * @param \App\Service\Generator\Mapper\WhirlpoolToProductMapper $whirlpoolMapper
     * @param \App\Service\VariantGeneratorService $variantGeneratorService
     */
    public function __construct(
        private readonly WhirlpoolToProductMapper $whirlpoolMapper,
        private readonly VariantGeneratorService $variantGeneratorService,
    ) {
        $this->setClassName(Whirlpool::class);
    }

    /**
     * @param \OpenDxp\Model\DataObject\Whirlpool $object
     *
     * @throws \OpenDxp\Model\Element\DuplicateFullPathException
     * @throws \Exception
     * @return \OpenDxp\Model\DataObject\Product
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

            // save master product
            Logger::notice(sprintf('[WhirlpoolToProductGenerator] - Save product: %s', $product->getKey()));
            VersionHelper::useVersioning(function () use ($product) {
                $product->save();
            }, false);

            // generate variants from RoyalFilterSetups
            Logger::notice(sprintf('[WhirlpoolToProductGenerator] - Processing variants for product: %s', $product->getKey()));
            $this->variantGeneratorService->processVariants($product);

            // save product on whirlpool
            $object->setProduct($product);
            VersionHelper::useVersioning(function () use ($object) {
                $object->save();
            }, false);
        });

        return $product;
    }

    /**
     * @param \OpenDxp\Model\DataObject\Listing|\OpenDxp\Model\Document\Listing $list
     */
    public function prepareListConditions(\OpenDxp\Model\DataObject\Listing|Listing $list): void
    {
        $list->setUnpublished(false);
        $list->addConditionParam('generateAsProduct IS TRUE');
        $list->addConditionParam('product__id IS NULL');
        $list->addConditionParam('royalFilterSetup__id IS NOT NULL');
    }
}
