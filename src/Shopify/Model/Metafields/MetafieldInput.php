<?php

namespace App\Shopify\Model\Metafields;

class MetafieldInput
{
    public function __construct(
        private ?string $id = null,
        private ?string $key = null,
        private ?string $namespace = null,
        private ?string $type = null,
        private ?string $value = null,
    ) {
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    public function getAsArray(): array
    {
        return [
            'id' => $this->id,
            'key' => $this->key,
            'namespace' => $this->namespace,
            'type' => $this->type,
            'value' => $this->value,
        ];
    }
}
