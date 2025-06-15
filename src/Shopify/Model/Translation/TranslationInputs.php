<?php

namespace App\Shopify\Model\Translation;

use App\Shopify\Model\IShopifyModel;

class TranslationInputs implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Translation\TranslationInput[] $createTranslationInput
     */
    public function __construct(
        private array $createTranslationInput = []
    ) {
    }

    /**
     * @return \App\Shopify\Model\Translation\TranslationInput[]
     */
    public function getTranslationInput(): array
    {
        return $this->createTranslationInput;
    }

    /**
     * @param array $createTranslationInput
     *
     * @return void
     */
    public function setTranslationInput(array $createTranslationInput): void
    {
        $this->createTranslationInput = $createTranslationInput;
    }

    /**
     * @param \App\Shopify\Model\Translation\TranslationInput $input
     *
     * @return void
     */
    public function addTranslationInput(TranslationInput $input): void
    {
        $this->createTranslationInput[] = $input;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getAsArray(int $limit = 100): array
    {
        $data = [];

        // get first
        if ($limit === 1) {
            return reset($this->createTranslationInput)->getAsArray();
        }

        $i = 0;
        foreach ($this->getTranslationInput() as $input) {
            if ($i++ < $limit) {
                $data[] = $input->getAsArray();
            }
        }

        return $data;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return count($this->getTranslationInput());
    }
}
