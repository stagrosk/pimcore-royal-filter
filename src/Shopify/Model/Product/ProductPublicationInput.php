<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\IShopifyModel;
use DateTime;
use DateTimeInterface;

class ProductPublicationInput implements IShopifyModel
{
    /**
     * @param string $publicationId
     * @param \DateTime|null $publishDate
     */
    public function __construct(
        private string $publicationId,
        private ?DateTime $publishDate = null
    ) {
    }

    /**
     * @return string
     */
    public function getPublicationId(): string
    {
        return $this->publicationId;
    }

    /**
     * @param string $publicationId
     *
     * @return void
     */
    public function setPublicationId(string $publicationId): void
    {
        $this->publicationId = $publicationId;
    }

    /**
     * @return \DateTime|null
     */
    public function getPublishDate(): ?DateTime
    {
        return $this->publishDate;
    }

    /**
     * @param \DateTime|null $publishDate
     *
     * @return void
     */
    public function setPublishDate(?DateTime $publishDate): void
    {
        $this->publishDate = $publishDate;
    }

    /**
     * @return string[]
     */
    public function getAsArray(): array
    {
        return [
            'publicationId' => $this->getPublicationId(),
            'publishDate' => ($this->getPublishDate() ?? new DateTime())->format(DateTimeInterface::ATOM),
        ];
    }
}
