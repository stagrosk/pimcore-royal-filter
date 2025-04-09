<?php

namespace App\Shopify\Model\Collection;

use App\Shopify\Model\IShopifyModel;

class CollectionPublishInput implements IShopifyModel
{
    /**
     * @param string $id
     * @param \App\Shopify\Model\Collection\CollectionPublicationInput $publicationInput
     */
    public function __construct(
        private string $id,
        private CollectionPublicationInput $publicationInput,
    ) {
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return \App\Shopify\Model\Collection\CollectionPublicationInput
     */
    public function getPublicationInput(): CollectionPublicationInput
    {
        return $this->publicationInput;
    }

    /**
     * @param \App\Shopify\Model\Collection\CollectionPublicationInput $publicationInput
     *
     * @return void
     */
    public function setPublicationInput(CollectionPublicationInput $publicationInput): void
    {
        $this->publicationInput = $publicationInput;
    }

    /**
     * @return string[]
     */
    public function getAsArray(): array
    {
        return [
            'id' => $this->id,
            'collectionPublications' => $this->publicationInput->getAsArray(),
        ];
    }
}
