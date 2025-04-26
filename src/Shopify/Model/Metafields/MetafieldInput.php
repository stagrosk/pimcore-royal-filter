<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

/**
 * Definition for product and variants directly
 */
class MetafieldInput implements IShopifyModel
{
    /**
     * @param string|null $id
     * @param string|null $key
     * @param string|null $namespace
     * @param string|null $type
     * @param string|int|float|bool|array|null $value
     */
    public function __construct(
        private ?string $id = null,
        private ?string $key = null,
        private ?string $namespace = null,
        private ?string $type = null,
        private string|int|float|bool|array|null $value = null,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];

        if ($this->getId() !== null) {
            $data['id'] = $this->getId();
        } else {
            if ($this->getKey() !== null) {
                $data['key'] = $this->getKey();
            }

            if ($this->getNamespace() !== null) {
                $data['namespace'] = $this->getNamespace();
            }

            if ($this->getType() !== null) {
                $data['type'] = strtolower($this->getType());
            }
        }

        $data['value'] = $this->getValue();

        return $data;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): void
    {
        $this->key = $key;
    }

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    public function setNamespace(?string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): string|int|float|bool|array|null
    {
        return $this->value;
    }

    public function setValue(string|int|float|bool|array|null $value): void
    {
        $this->value = $value;
    }
}
