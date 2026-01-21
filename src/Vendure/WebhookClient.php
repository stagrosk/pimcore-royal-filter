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
            'base_uri' => $vendureHost
        ]);
    }

    /**
     * Send webhook to Vendure to trigger product sync
     *
     * @param array $payload
     *
     * @return void
     */
    public function sendToVendureWebhook(array $payload): void
    {
        $url = '/pimcore/trigger-sync';

        $this->logger->info('[VendureWebhook] Sending webhook request', [
            'url' => $this->httpClient->getConfig('base_uri') . $url,
            'secret' => substr($this->vendureWebhookSecret, 0, 5) . '***',
            'secret_length' => strlen($this->vendureWebhookSecret),
            'payload' => $payload,
        ]);

        try {
            $response = $this->httpClient->post($url, [
                'json' => $payload,
                'query' => [
                    'secret' => $this->vendureWebhookSecret
                ]
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