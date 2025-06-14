<?php

namespace App\Shopify\Model\Metafields;

enum MetafieldOwnerTypeEnum: string
{
    case API_PERMISSION = 'API_PERMISSION';
    case ARTICLE = 'ARTICLE';
    case BLOG = 'BLOG';
    case CARTTRANSFORM = 'CARTTRANSFORM';
    case CATEGORY = 'COLLECTION'; // collection / category
    case COMPANY = 'COMPANY';
    case COMPANY_LOCATION = 'COMPANY_LOCATION';
    case CUSTOMER = 'CUSTOMER';
    case DELIVERY_CUSTOMIZATION = 'DELIVERY_CUSTOMIZATION';
    case DISCOUNT = 'DISCOUNT';
    case DRAFTORDER = 'DRAFTORDER';
    case FULFILLMENT_CONSTRAINT_RULE = 'FULFILLMENT_CONSTRAINT_RULE';
    case GIFT_CARD_TRANSACTION = 'GIFT_CARD_TRANSACTION';
    case LOCATION = 'LOCATION';
    case MARKET = 'MARKET';
    case ORDER = 'ORDER';
    case ORDER_ROUTING_LOCATION_RULE = 'ORDER_ROUTING_LOCATION_RULE';
    case PAGE = 'PAGE';
    case PAYMENT_CUSTOMIZATION = 'PAYMENT_CUSTOMIZATION';
    case PRODUCT = 'PRODUCT';
    case PRODUCTVARIANT = 'PRODUCTVARIANT';
    case SELLING_PLAN = 'SELLING_PLAN';
    case SHOP = 'SHOP';
    case VALIDATION = 'VALIDATION';

    public static function getValues(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }
        return $values;
    }
}
