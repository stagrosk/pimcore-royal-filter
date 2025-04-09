<?php

namespace App\Shopify\Model\Media;

use App\Shopify\Model\IShopifyModel;

class CreateMediaInputs implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Media\CreateMediaInput[] $createMediaInput
     */
    public function __construct(
        private array $createMediaInput = []
    ) {
    }

    /**
     * @return \App\Shopify\Model\Media\CreateMediaInput[]
     */
    public function getCreateMediaInput(): array
    {
        return $this->createMediaInput;
    }

    /**
     * @param array $createMediaInput
     *
     * @return void
     */
    public function setCreateMediaInput(array $createMediaInput): void
    {
        $this->createMediaInput = $createMediaInput;
    }

    /**
     * @param \App\Shopify\Model\Media\CreateMediaInput $input
     *
     * @return void
     */
    public function addMediaInput(CreateMediaInput $input): void
    {
        $this->createMediaInput[] = $input;
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getAsArray(int $limit = 10): array
    {
        $data = [];

        // get first
        if ($limit === 1) {
            return reset($this->createMediaInput)->getAsArray();
        }

        $i = 0;
        foreach ($this->getCreateMediaInput() as $input) {
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
        return count($this->getCreateMediaInput());
    }
}
