<?php

namespace App\Service\Generator\Mapper;

use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Whirlpool;
use Pimcore\Tool;
use Pimcore\Translation\Translator;

class WhirlpoolToProductMapper implements MapperInterface
{
    /**
     * @param \Pimcore\Translation\Translator $translator
     * @param \App\Service\Generator\Mapper\FilterToProductMapper $filterToProductMapper
     */
    public function __construct(
        private Translator $translator,
        private FilterToProductMapper $filterToProductMapper
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool|\Pimcore\Model\DataObject\Concrete $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(Whirlpool|Concrete $object, Product $product): Product
    {
        $royalFilterSetup = $object->getRoyalFilterSetup();
        $equipment1 = $object->getEquipBody1();
        $equipment2 = $object->getEquipBody2();

        // map all from filter
        $this->filterToProductMapper->mapObjectToProduct($royalFilterSetup, $product);

        // title
        foreach (Tool::getValidLanguages() as $language) {
            $product->setTitle($this->prepareTitle($object, $language), $language);
            $product->setShortDescription($object->getDescription($language), $language);
        }

        // base
        $product->setSku($object->getId());

        // images
        $images = array_merge($object->getImages(), $royalFilterSetup->getImages(), $equipment1->getImages(), $equipment2->getImages());
        $product->setImages($images); // media - all images in shopify

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\Whirlpool|\Pimcore\Model\DataObject\Concrete $object
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(Whirlpool|Concrete $object, string $language): string
    {
        $codes = [];
        foreach ($object->getPaperCartridges() as $cartridge) {
            foreach ($cartridge->getCodes() as $code) {
                if ($code->getShowInTitle() === true) {
                    $codes[] = $code;
                }
            }
        }

        // unique
        $codes = array_unique($codes);

        return $this->translator->trans('product_title_whirlpool', [
            'title' => $object->getTitle($language),
            'codes' => $codes,
        ]);
    }
}
