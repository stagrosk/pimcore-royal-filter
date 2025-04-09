<?php

namespace App\Shopify\Model\Seo;

class SeoInput
{
    /**
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(
        public ?string $title = null,
        public ?string $description = null
    ) {
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return void
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}
