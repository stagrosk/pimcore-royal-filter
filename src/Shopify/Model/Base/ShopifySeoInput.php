<?php

namespace App\Shopify\Model\Base;

class ShopifySeoInput
{
    public function __construct(
        private string $title,
        private string $description,
    ) {
    }

    public function getAsArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
