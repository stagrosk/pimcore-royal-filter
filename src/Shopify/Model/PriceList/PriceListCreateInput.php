<?php

namespace App\Shopify\Model\PriceList;

class PriceListCreateInput
{
    private ?string $currency;

    private ?string $name;

    private ?PriceListParentCreateInput $parent;

    /**
     * @param string|null $currency
     * @param string|null $name
     * @param \App\Shopify\Model\PriceList\PriceListParentCreateInput|null $parent
     */
    public function __construct(
        ?string                     $currency = 'EUR',
        ?string                     $name = null,
        ?PriceListParentCreateInput $parent = null
    ) {
        $this->currency = $currency;
        $this->name = $name;
        $this->parent = $parent;

        if (empty($this->getName())) {
            $this->name = 'PriceList' . uniqid();
        }

        if (empty($this->getParent())) {
            $this->parent = new PriceListParentCreateInput();
        }
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];
        $data['currency'] = $this->getCurrency();
        $data['name'] = $this->getName();
        $data['parent'] = $this->getParent()->getAsArray();

        return $data;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getParent(): ?PriceListParentCreateInput
    {
        return $this->parent;
    }

    public function setParent(?PriceListParentCreateInput $parent): void
    {
        $this->parent = $parent;
    }
}
