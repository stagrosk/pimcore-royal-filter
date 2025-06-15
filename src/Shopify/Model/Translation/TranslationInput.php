<?php

namespace App\Shopify\Model\Translation;

use App\Shopify\Model\IShopifyModel;
use stdClass;

class TranslationInput implements IShopifyModel
{
    /**
     * @param string $key
     * @param string $locale
     * @param string $value
     * @param string $translatableContentDigest
     * @param string|null $marketId
     */
    public function __construct(
        private string $key = '',
        private string $locale = '',
        private string $value = '',
        private string $translatableContentDigest = '',
        private ?string $marketId = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [
            'key' => $this->getKey(),
            'locale' => $this->getLocale(),
            'value' => $this->getValue(),
            'translatableContentDigest' => $this->getTranslatableContentDigest(),
        ];

        if (!empty($this->getMarketId())) {
            $data['marketId'] = $this->getMarketId();
        }

        return $data;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getMarketId(): ?string
    {
        return $this->marketId;
    }

    public function setMarketId(?string $marketId): void
    {
        $this->marketId = $marketId;
    }

    public function getTranslatableContentDigest(): ?string
    {
        return $this->translatableContentDigest;
    }

    public function setTranslatableContentDigest(?string $translatableContentDigest): void
    {
        $this->translatableContentDigest = $translatableContentDigest;
    }
}
