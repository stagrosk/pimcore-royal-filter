<?php

namespace App\Shopify\Graphql\Mutation;

use App\Shopify\Graphql\GraphqlClient;
use PHPShopify\Exception\ApiException;
use PHPShopify\Exception\CurlException;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Psr\Log\LoggerInterface;

abstract class BaseMutation implements ShopifyGraphqlMutationInterface
{
    public const PUBLICATIONS = [
        'store' => [
            'id' => 'gid://shopify/Publication/253529555287',
            'name' => 'Online Store',
        ],
        'shop' => [
            'id' => 'gid://shopify/Publication/253529620823',
            'name' => 'Shop',
        ],
        'point' => [
            'id' => 'gid://shopify/Publication/253529653591',
            'name' => 'Point of Sale',
        ]
    ];

    /**
     * @param \App\Shopify\Graphql\GraphqlClient $client
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        private readonly GraphqlClient $client,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @return array
     */
    public function callAction(AbstractObject|array $object): array
    {
        try {
            $client = $this->client->getClient();

            $variables = $this->getVariables($object);
            if (isset($variables['multiCall'])) {
                $results = [
                    'multiCall' => [],
                ];
                foreach ($variables['inputs'] as $input) {
                    $results['multiCall'][] = [
                        'input' => $input,
                        'response' => $client->GraphQL()->post($this->getMutation(), null, null, $input)
                    ];
                }

                return $results;
            }

            return $client->GraphQL()->post($this->getMutation(), null, null, $this->getVariables($object));
        } catch (ApiException|CurlException $e) {
            $this->logger->error('[Query] Error: ' . $e->getMessage());
        }

        return [];
    }
}
