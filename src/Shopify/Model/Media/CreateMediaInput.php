<?php

namespace App\Shopify\Model\Media;

use App\Shopify\Model\IShopifyModel;

class CreateMediaInput implements IShopifyModel
{
    /**
     * @param string $originalSource
     * @param \App\Shopify\Model\Media\MediaContentType $mediaContentType
     * @param string|null $alt
     */
    public function __construct(
        public string $originalSource,
        public MediaContentType $mediaContentType,
        public ?string $alt = null,
    ) {
    }

    public function getOriginalSource(): string
    {
        return $this->originalSource;
    }

    public function setOriginalSource(string $originalSource): void
    {
        $this->originalSource = $originalSource;
    }

    public function getMediaContentType(): MediaContentType
    {
        return $this->mediaContentType;
    }

    public function setMediaContentType(MediaContentType $mediaContentType): void
    {
        $this->mediaContentType = $mediaContentType;
    }

    public function getAlt(): ?string
    {
        return $this->alt;
    }

    public function setAlt(?string $alt): void
    {
        $this->alt = $alt;
    }

    public function getAsArray(): array
    {
        return [
            'originalSource' => $this->getOriginalSource(),
            'mediaContentType' => $this->getMediaContentType()->getType(),
            'alt' => $this->getAlt(),
        ];
    }
}
