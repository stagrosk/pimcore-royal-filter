<?php

namespace App\Shopify\Graphql\Mutation;

use App\Shopify\Graphql\GraphqlClient;
use PHPShopify\Exception\ApiException;
use PHPShopify\Exception\CurlException;
use Pimcore\Model\DataObject\AbstractObject;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\Notification\Service\NotificationService;
use Pimcore\Model\User;
use Pimcore\Security\User\TokenStorageUserResolver;
use Psr\Log\LoggerInterface;
use function PHPUnit\Framework\throwException;

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
     * @param \Pimcore\Model\Notification\Service\NotificationService $notificationService
     * @param \Pimcore\Security\User\TokenStorageUserResolver $tokenStorageUserResolver
     */
    public function __construct(
        private readonly GraphqlClient $client,
        private readonly LoggerInterface $logger,
        private readonly NotificationService $notificationService,
        private readonly TokenStorageUserResolver $tokenStorageUserResolver
    ) {
    }

    /**
     * @param \Pimcore\Model\DataObject\AbstractObject|array $object
     *
     * @throws \Exception
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

            $result = $client->GraphQL()->post($this->getMutation(), null, null, $this->getVariables($object));

            // try to extract userErrors -> if there is any throw it
            $userErrors = $this->extractUserErrorMessages($result);
            if (!empty($userErrors)) {
                $this->sendErrorsToUser($userErrors);
            }

            return $result;
        } catch (ApiException|CurlException $e) {
            $this->logger->error('[Query] Error: ' . $e->getMessage());
            $this->sendErrorsToUser([$e->getMessage()]);
        }

        return [];
    }

    /**
     * @param array $errors
     *
     * @throws \Doctrine\DBAL\Exception
     * @return void
     */
    private function sendErrorsToUser(array $errors): void
    {
        $currentUser = $this->tokenStorageUserResolver->getUser();
        $userId = null;
        if ($currentUser instanceof User) {
            $userId = $currentUser->getId();
        }
        $this->notificationService->sendToUser(
            $userId,
            0,
            'GraphQL Errors',
            nl2br(implode(PHP_EOL, $errors))
        );
    }

    private function extractUserErrorMessages(array $data): array
    {
        $allMessages = [];
        foreach ($data as $key => $value) {
            if ($key === 'userErrors' && is_array($value)) {
                foreach ($value as $errorItem) {
                    if (is_array($errorItem) && isset($errorItem['message'])) {
                        $allMessages[] = $errorItem['message'];
                    }
                }
            }
            elseif (is_array($value)) {
                $allMessages = array_merge($allMessages, $this->extractUserErrorMessages($value));
            }
        }

        return $allMessages;
    }
}
