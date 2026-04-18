<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Pimcore\Event\DataObjectEvents;
use Pimcore\Event\Model\DataObjectEvent;
use Pimcore\Event\Model\TranslationEvent;
use Pimcore\Event\TranslationEvents;
use Pimcore\Model\DataObject\NavigationItem;
use Pimcore\Model\DataObject\WebsiteConfiguration;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class StorefrontCacheInvalidator implements EventSubscriberInterface
{
    private const NAVIGATION_IDENTIFIERS = ['root', 'navigationTopbar', 'navigationFooter'];
    private const ESHOP_TRANSLATION_PREFIX = 'eshop-';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
        private readonly string $storefrontUrl,
        private readonly string $secret,
    ) {
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
            $response = $this->httpClient->request('POST', rtrim($this->storefrontUrl, '/') . '/api/cache-invalidate', [
                'headers' => [
                    'x-cache-secret' => $this->secret,
                ],
                'json' => ['scope' => $scope],
                'timeout' => 5,
            ]);

            $status = $response->getStatusCode();
            $this->logger->info("[StorefrontCache] Invalidated '{$scope}' (HTTP {$status})");
        } catch (\Throwable $e) {
            $this->logger->warning("[StorefrontCache] Failed for '{$scope}': " . $e->getMessage());
        }
    }
}
