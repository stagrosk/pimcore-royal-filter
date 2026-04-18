<?php

declare(strict_types=1);

namespace App\Vendure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

class WebhookClient
{
    private Client $httpClient;

    public function __construct(
        string $vendureHost,
        private readonly string $vendureWebhookSecret,
        private readonly LoggerInterface $logger
    ) {
        $this->httpClient = new Client([
            'base_uri' => $vendureHost,
            'timeout' => 10,
            'connect_timeout' => 5,
        ]);
    }

    public function sendToVendureWebhook(array $payload): void
    {
        $url = '/pimcore/trigger-sync';

        $this->logger->info('[VendureWebhook] Sending webhook request', [
            'payload' => $payload,
        ]);

        try {
            $response = $this->httpClient->post($url, [
                'json' => $payload,
                'headers' => [
                    'X-Webhook-Secret' => $this->vendureWebhookSecret,
                ],
            ]);

            $this->logger->info('[VendureWebhook] Webhook sent successfully', [
                'status' => $response->getStatusCode(),
                'payload' => $payload,
            ]);
        } catch (GuzzleException $e) {
            $this->logger->error('[VendureWebhook] Error while sending webhook', [
                'error' => $e->getMessage(),
                'code' => $e->getCode(),
                'payload' => $payload,
            ]);
        }
    }
}