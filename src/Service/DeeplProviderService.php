<?php

declare(strict_types=1);

namespace App\Service;

use OpendxpTranslationBundle\Exception\TranslationException;
use OpendxpTranslationBundle\Provider\AbstractProvider;
use OpendxpTranslationBundle\Provider\FormalityProviderInterface;

class DeeplProviderService extends AbstractProvider implements FormalityProviderInterface
{

    public function __construct(
        private readonly string $authKey,
        private readonly string $glossaryKey,
        private readonly string $endpoint
    ) {
    }

    public function translate(string $data, string $targetLanguage, ?string $sourceLanguage = null): string
    {
        try {
            $targetLang = DeeplLanguageMap::resolve($targetLanguage);

            $params = [
                'text' => [$data],
                'target_lang' => $targetLang,
                'formality' => 'default',
            ];

            if ($sourceLanguage) {
                $params['source_lang'] = DeeplLanguageMap::resolveSource($sourceLanguage);
            }

            if (!empty($this->glossaryKey)) {
                $params['glossary_id'] = $this->glossaryKey;
            }

            $response = $this->getHttpClient()->request('POST', $this->endpoint, [
                'headers' => [
                    'Authorization' => 'DeepL-Auth-Key ' . $this->authKey,
                ],
                'json' => $params,
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
        } catch (\Throwable $exception) {
            throw new TranslationException($exception->getMessage());
        }

        if (!isset($data['translations'][0]['text'])) {
            throw new TranslationException(sprintf(
                'Unexpected DeepL API response: %s',
                json_encode($data)
            ));
        }

        return $data['translations'][0]['text'];
    }

    public function getName(): string
    {
        return 'deepl';
    }

    // Formality fixed to 'default' - glossary handles formality concerns
    public function setFormality(?string $formality): FormalityProviderInterface
    {
        return $this;
    }
}
