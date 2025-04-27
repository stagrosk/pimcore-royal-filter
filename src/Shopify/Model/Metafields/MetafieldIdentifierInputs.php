<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldIdentifierInputs implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Metafields\MetafieldIdentifierInput[] $metafieldIdentifierInputs
     */
    public function __construct(
        private array $metafieldIdentifierInputs = []
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];
        foreach ($this->getMetafieldIdentifierInputs() as $input) {
            $data[] = $input->getAsArray();
        }

        return $data;
    }

    public function getMetafieldIdentifierInputs(): array
    {
        return $this->metafieldIdentifierInputs;
    }

    public function setMetafieldIdentifierInputs(array $metafieldIdentifierInputs): void
    {
        $this->metafieldIdentifierInputs = $metafieldIdentifierInputs;
    }

    /**
     * @param \App\Shopify\Model\Metafields\MetafieldIdentifierInput $metafieldIdentifierInput
     *
     * @return void
     */
    public function addMetafieldIdentifierInput(MetafieldIdentifierInput $metafieldIdentifierInput): void
    {
        $this->metafieldIdentifierInputs[] = $metafieldIdentifierInput;
    }
}
