<?php

namespace App\Shopify\Service\Product\Variant;

use App\Shopify\Model\Media\CreateMediaInputs;
use App\Shopify\Model\Metafields\MetafieldInputs;
use App\Shopify\Model\Price\VariantPriceInput;
use App\Shopify\Model\Product\Variant\ProductVariantsBulkInput;
use App\Shopify\Model\Product\Variant\VariantOptionValueInput;
use App\Shopify\Service\Media\ShopifyMediaMapper;
use App\Shopify\Service\Metafields\ShopifyMetafieldsMapper;
use App\Shopify\Service\Price\ShopifyPriceMapper;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\PriceList;
use Pimcore\Model\DataObject\Product;

class ShopifyVariantMapper implements IShopifyVariantMapper
{
    /**
     * @param \App\Shopify\Service\Metafields\ShopifyMetafieldsMapper $metafieldsMapper
     * @param \App\Shopify\Service\Media\ShopifyMediaMapper $mediaMapper
     * @param \App\Shopify\Service\Price\ShopifyPriceMapper $priceMapper
     */
    public function __construct(
        private readonly ShopifyMetafieldsMapper $metafieldsMapper,
        private readonly ShopifyMediaMapper      $mediaMapper,
        private readonly ShopifyPriceMapper      $priceMapper,
    ) {
    }

    const DEFAULT_MAPPER_SERVICE_KEY = 'shopify_product';
    const PRODUCT_CLASS_ID = 'DEFAULT_PROD';
    const SHOPIFY_CHANNEL_KEY = 'shopify_1';

    public function getMapperServiceKey(): string
    {
        return self::DEFAULT_MAPPER_SERVICE_KEY;
    }

    public function getProductClassId(): string
    {
        return self::PRODUCT_CLASS_ID;
    }

    public function getShopifyChannelKey(): string
    {
        return self::SHOPIFY_CHANNEL_KEY;
    }

    /**
     * @param \App\Shopify\Model\Product\Variant\ProductVariantsBulkInput $input
     * @param \Pimcore\Model\DataObject\AbstractObject $object
     *
     * @throws \Exception
     * @return \App\Shopify\Model\Product\Variant\ProductVariantsBulkInput
     */
    public function getMappedObject(ProductVariantsBulkInput $input, AbstractObject $object): ProductVariantsBulkInput
    {
        /** @var Product $object */
        if (method_exists($input, 'setId')) {
            $input->setId($object->getApiId());
        }

        // base
//        $input->setBarcode();
        $input->setRequiresComponents(false);
        $input->setTaxable(true);
//        $input->setTaxCode();

        // media
//        $input->setMediaId();
        $createMediaInputs = $this->mediaMapper->getMappedObject(new CreateMediaInputs(), $object);
        if ($createMediaInputs->getCount() > 0) {
            $input->setMediaSrc($createMediaInputs->getAsArray(1)['originalSource']);
        }

        // metafields and options
        $metaFieldInputs = $this->metafieldsMapper->getMappedObject(new MetafieldInputs(), $object);
        if ($metaFieldInputs->getCount() > 0) {
            $input->setMetafields($metaFieldInputs);
        }

        $optionValues = [];
        /** @var \Pimcore\Model\DataObject\Fieldcollection\Data\VariantOption $variantOption */
        foreach ($object->getVariantOptions()?->getItems() ?? [] as $variantOption) {
            $optionValues[] = new VariantOptionValueInput($variantOption->getShopifyMetafieldDefinition()->getDescription(), $variantOption->getOptionValue());
        }
        $input->setOptionValues($optionValues);

        // price
        $basePriceList = PriceList::getByBasePricelist(true, 1);
        $variantPriceInput = $this->priceMapper->getMappedObject(new VariantPriceInput(), $object, $basePriceList);
        $input->setPrice($variantPriceInput->getPrice()?->getAmount());
        $input->setCompareAtPrice($variantPriceInput->getCompareAtPrice()?->getAmount());

        // inventory
//        $input->setInventoryItem();
//        $input->setInventoryQuantities();
//        $input->setInventoryPolicy();

        return $input;
    }
}
