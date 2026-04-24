<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

use OpendxpTranslationBundle\Exception\TranslationException;

class DeeplProvider extends AbstractProvider implements FormalityProviderInterface
{
    protected string $url = 'https://api.deepl.com/';
    protected string $formality = 'default';

    /**
     * @param string|null $formality
     *
     * @return $this
     */
    public function setFormality(?string $formality): self
    {
        $this->formality = $formality ?? $this->formality;

        return $this;
    }

    /**
     * @param string $data
     * @param string $targetLanguage
     *
     * @throws \OpendxpTranslationBundle\Exception\TranslationException
     * @return string
     */
    public function translate(string $data, string $targetLanguage): string
    {
        try {
            $response = $this->getHttpClient()->request(
                'POST',
                'v2/translate',
                [
                    'query' => [
                        'auth_key' => $this->apiKey,
                        'text' => $data,
                        'target_lang' => locale_get_primary_language($targetLanguage),
                        'formality' => $this->formality
                    ]
                ]
            );

            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
        } catch (\Throwable $exception) {
            throw new TranslationException($exception->getMessage());
        }

        return $data['translations'][0]['text'];
    }

    public function getName(): string
    {
        return 'deepl';
    }
}
