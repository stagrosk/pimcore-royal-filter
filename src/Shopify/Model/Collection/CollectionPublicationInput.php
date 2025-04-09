<?php

namespace App\Shopify\Model\Collection;

use App\Shopify\Model\IShopifyModel;

class CollectionPublicationInput implements IShopifyModel
{
    /**
     * @param string $publicationId
     */
    public function __construct(
        private string $publicationId
    ) {
    }

    /**
     * @return string
     */
    public function getPublicationId(): string
    {
        return $this->publicationId;
    }

    /**
     * @param string $publicationId
     *
     * @return void
     */
    public function setPublicationId(string $publicationId): void
    {
        $this->publicationId = $publicationId;
    }

    /**
     * @return string[]
     */
    public function getAsArray(): array
    {
        return [
            'publicationId' => $this->getPublicationId(),
        ];
    }
}
