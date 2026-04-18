<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\TranslationEvent;
use Pimcore\Event\TranslationEvents;
use Pimcore\Model\DataObject\NavigationItem;
use Pimcore\Model\DataObject\WebsiteConfiguration;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StorefrontCacheInvalidator implements EventSubscriberInterface
{
    private const NAVIGATION_IDENTIFIERS = ['root', 'navigationTopbar', 'navigationFooter'];
    private const ESHOP_TRANSLATION_PREFIX = 'eshop-';

    private Client $httpClient;

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly string $storefrontUrl,
        private readonly string $secret,
    ) {
        $this->httpClient = new Client([
            'timeout' => 5,
            'connect_timeout' => 3,
        ]);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            DataObjectEvents::POST_UPDATE => 'onDataObjectChange',
            DataObjectEvents::POST_ADD => 'onDataObjectChange',
            DataObjectEvents::POST_DELETE => 'onDataObjectChange',
            TranslationEvents::POST_SAVE => 'onTranslationChange',
            TranslationEvents::POST_DELETE => 'onTranslationChange',
        ];
    }

    public function onDataObjectChange(DataObjectEvent $event): void
    {
        $obj = $event->getObject();

        if ($obj instanceof NavigationItem) {
            $identifier = $obj->getIdentifier();
            if (in_array($identifier, self::NAVIGATION_IDENTIFIERS, true)) {
                $this->invalidate('navigation');
                return;
            }
            // also invalidate for child nav items (they live under root nav objects)
            $parent = $obj->getParent();
            if ($parent instanceof NavigationItem && in_array($parent->getIdentifier(), self::NAVIGATION_IDENTIFIERS, true)) {
                $this->invalidate('navigation');
            }
            return;
        }

        if ($obj instanceof WebsiteConfiguration) {
            $this->invalidate('config');
        }
    }

    public function onTranslationChange(TranslationEvent $event): void
    {
        $key = $event->getTranslation()->getKey();
        if (str_starts_with($key, self::ESHOP_TRANSLATION_PREFIX)) {
            $this->invalidate('translations');
        }
    }

    private function invalidate(string $scope): void
    {
        if (empty($this->storefrontUrl) || empty($this->secret)) {
            return;
        }

        try {
            $response = $this->httpClient->post(rtrim($this->storefrontUrl, '/') . '/api/cache-invalidate', [
                'headers' => [
                    'x-cache-secret' => $this->secret,
                    'Content-Type' => 'application/json',
                ],
                'json' => ['scope' => $scope],
            ]);

            $this->logger->info("[StorefrontCache] Invalidated '{$scope}' (HTTP {$response->getStatusCode()})");
        } catch (GuzzleException $e) {
            $this->logger->warning("[StorefrontCache] Failed for '{$scope}': " . $e->getMessage());
        }
    }
}
