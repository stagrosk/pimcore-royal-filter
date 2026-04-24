<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

use OpendxpTranslationBundle\Exception\TranslationException;

class GoogleProvider extends AbstractProvider
{
    protected string $url = 'https://www.googleapis.com/';

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
                'GET',
                'language/translate/v2',
                [
                    'query' => [
                        'key' => $this->apiKey,
                        'q' => $data,
                        'source' => '',
                        'target' => locale_get_primary_language($targetLanguage),
                    ]
                ]
            );
            $body = $response->getBody()->getContents();
            $data = json_decode($body, true);

            if ($data['error']) {
                throw new TranslationException();
            }
        } catch (\Throwable $exception) {
            throw new TranslationException();
        }

        return $data['data']['translations'][0]['translatedText'];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'google_translate';
    }
}
