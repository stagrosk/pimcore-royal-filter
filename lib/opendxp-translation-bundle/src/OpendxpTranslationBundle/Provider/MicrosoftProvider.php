<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

use OpendxpTranslationBundle\Exception\TranslationException;

class MicrosoftProvider extends AbstractProvider
{
    protected string $url = 'https://api.cognitive.microsofttranslator.com/';

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
                'translate',
                [
                    'headers' => [
                        'Ocp-Apim-Subscription-Key' => $this->apiKey,
                        'X-ClientTraceId' => md5(uniqid((string) rand(), true)),
                        'Content-Type' => 'application/json',
                    ],
                    'json' => [['Text' => $data]],
                    'query' => [
                        'api-version' => '3.0',
                        'to' => locale_get_primary_language($targetLanguage),
                    ],
                ]
            );
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);
        } catch (\Throwable $exception) {
            throw new TranslationException();
        }

        return $data[0]['translations'][0]['text'];
    }

    public function getName(): string
    {
        return 'microsoft_translate';
    }
}
