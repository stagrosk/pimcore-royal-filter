<?php

namespace App\Shopify\Graphql;

use PHPShopify\ShopifySDK;

readonly class GraphqlClient
{
    /**
     * @param string $shop
     * @param string $apiToken
     */
    public function __construct(
        private string $shop,
        private string $apiToken,
    ) {
    }

    public function getClient(): ShopifySDK
    {
        $config = array(
            'ShopUrl' => $this->shop,
            'AccessToken' => $this->apiToken,
        );

        return ShopifySDK::config($config);
    }
}
