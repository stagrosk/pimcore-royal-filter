<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldDefinitionInput implements IShopifyModel
{
    /**
     * @param string $name
     * @param string $key
     * @param string $type
     * @param string $namespace
     * @param bool $pin
     * @param string|null $description
     * @param \App\Shopify\Model\Metafields\MetafieldAccessInput|null $access
     * @param \App\Shopify\Model\Metafields\MetafieldOwnerTypeEnum|null $ownerType
     * @param \App\Shopify\Model\Metafields\MetafieldCapabilityCreateInput|null $capabilities
     * @param array|null $constraints
     * @param array|null $validations
     */
    public function __construct(
        public string $name = '',
        public string $key = '',
        public string $type = '',
        public string $namespace = 'custom',
        public bool $pin = false,
        public ?string $description = null,
        public ?MetafieldAccessInput $access = null,
        public ?MetafieldOwnerTypeEnum $ownerType = null,
        public ?MetafieldCapabilityCreateInput $capabilities = null,
        public ?array $constraints = null,
        public ?array $validations = null,
    ) {
        if (empty($capabilities)) {
            $this->capabilities = new MetafieldCapabilityCreateInput();
        }
    }

    /**
     * @param bool $isUpdate
     *
     * @return array
     */
    public function getAsArray(bool $isUpdate = false): array
    {
        $data = [
            'name' => $this->getName(),
            'key' => $this->getKey(),
            'namespace' => $this->getNamespace(),
            'pin' => $this->getPin(),
            'description' => $this->getDescription(),
            'access' => $this->getAccess()->getAsArray(),
            'ownerType' => $this->getOwnerType()->value,
//            'capabilities' => $this->getCapabilities()->getAsArray()
        ];

        if (!$isUpdate) {
            $data['type'] = $this->getType();
        }

//        if (!empty($this->getConstraints())) {
//            foreach ($this->getConstraints() as $constraint) {
//                $data['constraints'][] = $constraint->getAsArray();
//            }
//        }
//
//        if (!empty($this->getValidations())) {
//            foreach ($this->getValidations() as $validation) {
//                $data['validations'][] = $validation->getAsArray();
//            }
//        }

        return $data;
    }

    public function getAccess(): MetafieldAccessInput
    {
        return $this->access;
    }

    public function setAccess(MetafieldAccessInput $access): void
    {
        $this->access = $access;
    }

    public function getCapabilities(): MetafieldCapabilityCreateInput
    {
        return $this->capabilities;
    }

    public function setCapabilities(MetafieldCapabilityCreateInput $capabilities): void
    {
        $this->capabilities = $capabilities;
    }

    public function getConstraints(): ?array
    {
        return $this->constraints;
    }

    public function setConstraints(?array $constraints): void
    {
        $this->constraints = $constraints;
    }

    public function addConstraint(MetafieldDefinitionConstraintsInput $constraint): void
    {
        $this->constraints[] = $constraint;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key): void
    {
        $this->key = $key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getOwnerType(): MetafieldOwnerTypeEnum
    {
        return $this->ownerType;
    }

    public function setOwnerType(MetafieldOwnerTypeEnum $ownerType): void
    {
        $this->ownerType = $ownerType;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getValidations(): ?array
    {
        return $this->validations;
    }

    public function setValidations(?array $validations): void
    {
        $this->validations = $validations;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function setNamespace(string $namespace): void
    {
        $this->namespace = $namespace;
    }

    public function getPin(): bool
    {
        return $this->pin;
    }

    public function setPin(bool $pin): void
    {
        $this->pin = $pin;
    }
}
