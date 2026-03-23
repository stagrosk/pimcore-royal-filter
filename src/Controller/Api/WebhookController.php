<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Vendure\WebhookHandler;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/vendure')]
class WebhookController extends AbstractController
{
    public function __construct(
        private readonly WebhookHandler $webhookHandler,
        private readonly LoggerInterface $logger,
        private readonly string $vendureWebhookSecret
    ) {
    }

    #[Route('/webhook', name: 'api_vendure_webhook', methods: ['POST'])]
    public function handleWebhook(Request $request): JsonResponse
    {
        if (!$this->isValidRequest($request)) {
            $this->logger->warning('[VendureWebhook] Unauthorized webhook request');
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        try {
            $payload = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error('[VendureWebhook] Invalid JSON payload', ['error' => $e->getMessage()]);
            return new JsonResponse(['error' => 'Invalid JSON payload'], Response::HTTP_BAD_REQUEST);
        }

        if (empty($payload['entity']) || empty($payload['action'])) {
            return new JsonResponse(['error' => 'Missing required fields: entity, action'], Response::HTTP_BAD_REQUEST);
        }

        $this->logger->info('[VendureWebhook] Received webhook', [
            'entity' => $payload['entity'],
            'action' => $payload['action'],
            'vendureId' => $payload['vendureId'] ?? null,
            'pimcoreId' => $payload['pimcoreId'] ?? null,
        ]);

        try {
            $result = $this->webhookHandler->handle($payload);
            return new JsonResponse($result);
        } catch (\Exception $e) {
            $this->logger->error('[VendureWebhook] Error processing webhook', [
                'error' => $e->getMessage(),
                'entity' => $payload['entity'] ?? null,
            'action' => $payload['action'] ?? null,
            'pimcoreId' => $payload['pimcoreId'] ?? null,
            ]);
            return new JsonResponse(['error' => 'Processing failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function isValidRequest(Request $request): bool
    {
        $secret = $request->headers->get('X-Webhook-Secret');

        if ($secret === null) {
            return false;
        }

        return hash_equals($this->vendureWebhookSecret, $secret);
    }
}
