<?php

declare(strict_types=1);

namespace OpendxpTranslationBundle\Provider;

use GuzzleHttp\Client;

abstract class AbstractProvider implements ProviderInterface
{
    protected string $url = '';

    protected string $apiKey;

    /**
     * @param string $apiKey
     *
     * @return $this
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
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
