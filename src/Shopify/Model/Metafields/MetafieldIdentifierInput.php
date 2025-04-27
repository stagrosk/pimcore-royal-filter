<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldIdentifierInput implements IShopifyModel
{
    /**
     * @param string $key
     * @param string $namespace
     * @param string $ownerId
     */
    public function __construct(
        private string $key,
        private string $namespace,
        private string $ownerId,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'key' => $this->getKey(),
            'namespace' => $this->getNamespace(),
            'ownerId' => $this->getOwnerId(),
        ];
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     *
     * @return void
     */
    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId
     *
     * @return void
     */
    public function setOwnerId(string $ownerId): void
    {
        $this->ownerId = $ownerId;
    }
}
