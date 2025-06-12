<?php

namespace App\Service;

use GuzzleHttp\Client;
use Pimcore\Tool;

readonly class DeeplService
{
    /**
     * @param \GuzzleHttp\Client $client
     * @param string $authKey
     * @param string $glossaryKey
     * @param string $endpoint
     */
    public function __construct(
        private Client $client,
        private string $authKey,
        private string $glossaryKey,
        private string $endpoint
    ) {
    }

    /**
     * @param string|null $text
     * @param string $targetLocale
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @return string|null
     */
    public function translate(?string $text, string $targetLocale): ?string
    {
        if (is_null($text)) {
            return null;
        }

        // use british english in case that target is english
        if ($targetLocale === 'en') {
            $targetLocale = 'en_gb';
        }

        $response = $this->client->request('POST', $this->endpoint, [
            'form_params' => [
                'auth_key' => $this->authKey,
                'text' => $text,
                'source_lang' => Tool::getDefaultLanguage(),
                'target_lang' => substr($targetLocale, 0, 2),
                'formality' => 'default',
                'glossary_id' => $this->glossaryKey
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['translations'][0]['text'];
    }
}
