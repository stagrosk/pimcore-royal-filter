<?php

declare(strict_types=1);

namespace App\Vendure;

use App\EventSubscriber\ProductSubscriber;
use OpenDxp\Model\DataObject\Product;
use Psr\Log\LoggerInterface;

class WebhookHandler
{
    public const ACTION_CREATED = 'created';
    public const ACTION_UPDATED = 'updated';
    public const ACTION_DELETED = 'deleted';

    public const ENTITY_PRODUCT = 'product';

    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly ProductSubscriber $productSubscriber
    ) {
    }

    /**
     * @param array{entity: string, action: string, vendureId?: string|int, pimcoreId?: int} $payload
     * @return array{success: bool, message: string}
     */
    public function handle(array $payload): array
    {
        return match ($payload['entity']) {
            self::ENTITY_PRODUCT => $this->handleProduct($payload),
            default => ['success' => false, 'message' => sprintf('Unknown entity type: %s', $payload['entity'])],
        };
    }

    private function handleProduct(array $payload): array
    {
        $pimcoreId = $payload['pimcoreId'] ?? null;
        $vendureId = $payload['vendureId'] ?? null;
        $action = $payload['action'];

        if (!$pimcoreId) {
            return ['success' => false, 'message' => 'Missing pimcoreId for product'];
        }

        $product = Product::getById((int) $pimcoreId);
        if (!$product instanceof Product) {
            return ['success' => false, 'message' => sprintf('Product with pimcoreId %d not found', $pimcoreId)];
        }

        if ($action === self::ACTION_DELETED) {
            $this->logger->info('[VendureWebhook] Delete action received for product', [
                'pimcoreId' => $pimcoreId,
            ]);
            return ['success' => true, 'message' => 'Delete acknowledged'];
        }

        if ($vendureId !== null && $product->getApiId() !== (string) $vendureId) {
            $product->setApiId((string) $vendureId);

            $this->productSubscriber->setSkipPushToQueue(true);

            try {
                $product->save();
            } finally {
                $this->productSubscriber->setSkipPushToQueue(false);
            }

            $this->logger->info('[VendureWebhook] Product updated from Vendure', [
                'pimcoreId' => $pimcoreId,
                'vendureId' => $vendureId,
            ]);

            return ['success' => true, 'message' => 'Product updated'];
        }

        return ['success' => true, 'message' => 'No changes needed'];
    }
}
