<?php

namespace OpendxpDeeplBundle\Service;

use GuzzleHttp\Client;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DeeplService
{
    protected string $url = 'https://api.deepl.com/';

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function translate(?string $text, string $targetLocale): ?string
    {
        if (is_null($text)) {
            return null;
        }

        $container = \OpenDxp::getContainer();
        $authKey = $container->getParameter('opendxp_deepl');

        $response = $this->getHttpClient()->request('POST', 'v2/translate', [
            'headers' => [
                'Authorization' => 'DeepL-Auth-Key ' . $authKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'text' => [$text],
                'target_lang' => substr($targetLocale, 0, 2),
            ]
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        return $data['translations'][0]['text'];
    }

    /**
     * @return \GuzzleHttp\Client
     */
    protected function getHttpClient(): Client
    {
        return new Client([
            'base_uri' => $this->url,
        ]);
    }
}
