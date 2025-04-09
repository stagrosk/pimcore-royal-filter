<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\Seo\SeoInput;

class ProductCreateInput extends ProductBaseInput {
    /**
     * @param string|null $category
     * @param array|null $collectionsToJoin
     * @param string|null $descriptionHtml
     * @param string|null $giftCardTemplateSuffix
     * @param string|null $handle
     * @param array|null $metafields
     * @param string|null $productType
     * @param bool|null $requiresSellingPlan
     * @param \App\Shopify\Model\Seo\SeoInput|null $seo
     * @param string|null $status
     * @param array|null $tags
     * @param string|null $templateSuffix
     * @param string|null $title
     * @param string|null $vendor
     * @param string|null $combinedListingRole
     * @param bool|null $giftCard
     * @param array|null $productOptions
     */
    public function __construct(
        ?string         $category = null,
        ?array          $collectionsToJoin = null,
        ?string         $descriptionHtml = null,
        ?string         $giftCardTemplateSuffix = null,
        ?string         $handle = null,
        ?array          $metafields = null,
        ?string         $productType = null,
        ?bool           $requiresSellingPlan = null,
        ?SeoInput       $seo = null,
        ?string         $status = null,
        ?array          $tags = null,
        ?string         $templateSuffix = null,
        ?string         $title = null,
        ?string         $vendor = null,
        private ?string $combinedListingRole = null,
        private ?bool   $giftCard = null,
        private ?array  $productOptions = null
    ) {
        parent::__construct(
            $category,
            $collectionsToJoin,
            $descriptionHtml,
            $giftCardTemplateSuffix,
            $handle,
            $metafields,
            $productType,
            $requiresSellingPlan,
            $seo,
            $status,
            $tags,
            $templateSuffix,
            $title,
            $vendor
        );
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = parent::getAsArray();

        if (!empty($this->getCombinedListingRole())) {
            $data['combinedListingRole'] = $this->getCombinedListingRole();
        }

        if (!empty($this->getGiftCard())) {
            $data['giftCard'] = $this->getGiftCard();
        }

        if (!empty($this->getProductOptions())) {
            $data['productOptions'] = $this->getProductOptions();
        }

        return $data;
    }

    public function getCombinedListingRole(): ?string
    {
        return $this->combinedListingRole;
    }

    public function setCombinedListingRole(?string $combinedListingRole): void
    {
        $this->combinedListingRole = $combinedListingRole;
    }

    public function getGiftCard(): ?bool
    {
        return $this->giftCard;
    }

    public function setGiftCard(?bool $giftCard): void
    {
        $this->giftCard = $giftCard;
    }

    public function getProductOptions(): ?array
    {
        return $this->productOptions;
    }

    public function setProductOptions(?array $productOptions): void
    {
        $this->productOptions = $productOptions;
    }
}
