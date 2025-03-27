<?php

namespace App\Service\Generator\Mapper;

use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\RoyalFilter;
use Pimcore\Tool;
use Pimcore\Translation\Translator;

class FilterToProductMapper implements MapperInterface
{
    /**
     * @param \Pimcore\Translation\Translator $translator
     */
    public function __construct(
        private Translator $translator,
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @return \Pimcore\Model\DataObject\Product
     */
    public function mapObjectToProduct(Concrete $object, Product $product): Product
    {
        foreach (Tool::getValidLanguages() as $language) {
            $product->setName($this->prepareTitle($object, $language), $language);
        }

        return $product;
    }

    /**
     * @param \Pimcore\Model\DataObject\RoyalFilter|\Pimcore\Model\DataObject\Concrete $object
     * @param string $language
     *
     * @return string
     */
    public function prepareTitle(RoyalFilter|Concrete $object, string $language): string
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

        return $this->translator->trans('product_title_product', [
            'dimensions' => $this->getProductDimensions($object),
            'codes' => $codes,
        ]);
    }

    /**
     * @param \Pimcore\Model\DataObject\RoyalFilter|\Pimcore\Model\DataObject\Concrete $object
     *
     * @return string
     */
    public function getProductDimensions(RoyalFilter|Concrete $object): string
    {

    }
}
