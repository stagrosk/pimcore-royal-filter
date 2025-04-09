<?php

namespace App\Shopify\Model\Metafields;

class MetafieldInputs
{
    /**
     * @param \App\Shopify\Model\Metafields\MetafieldInput[] $metafieldInputs
     */
    public function __construct(
        private array $metafieldInputs = []
    ) {
    }

    /**
     * @return \App\Shopify\Model\Metafields\MetafieldInput[]
     */
    public function getMetafieldInputs(): array
    {
        return $this->metafieldInputs;
    }

    /**
     * @param array $metafieldInputs
     *
     * @return void
     */
    public function setMetafieldInputs(array $metafieldInputs): void
    {
        $this->metafieldInputs = $metafieldInputs;
    }

    /**
     * @param \App\Shopify\Model\Metafields\MetafieldInput $input
     *
     * @return void
     */
    public function addMetaFieldInput(MetafieldInput $input): void
    {
        $this->metafieldInputs[] = $input;
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];
        foreach ($this->getMetafieldInputs() as $input) {
            $data[] = $input->getAsArray();
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->getMetafieldInputs());
    }
}
