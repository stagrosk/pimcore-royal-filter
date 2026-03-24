<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client;
use Pimcore\Tool;

readonly class DeeplService
{
    public function __construct(
        private Client $client,
        private string $authKey,
        private string $glossaryKey,
        private string $endpoint
    ) {
    }

    public function translate(?string $text, string $targetLocale): ?string
    {
        if (is_null($text)) {
            return null;
        }

        $targetLang = DeeplLanguageMap::resolve($targetLocale);

        $params = [
            'text' => [$text],
            'source_lang' => strtoupper(substr(Tool::getDefaultLanguage(), 0, 2)),
            'target_lang' => $targetLang,
            'formality' => 'default',
        ];

        if (!empty($this->glossaryKey)) {
            $params['glossary_id'] = $this->glossaryKey;
        }

        $response = $this->client->request('POST', $this->endpoint, [
            'headers' => [
                'Authorization' => 'DeepL-Auth-Key ' . $this->authKey,
            ],
            'json' => $params,
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['translations'][0]['text'])) {
            throw new \RuntimeException(sprintf(
                'Unexpected DeepL API response: %s',
                json_encode($data)
            ));
        }

        return $data['translations'][0]['text'];
    }
}
