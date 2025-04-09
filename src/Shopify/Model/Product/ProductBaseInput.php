<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\Seo\SeoInput;

class ProductBaseInput {
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
     */
    public function __construct(
        protected ?string   $category = null,
        protected ?array    $collectionsToJoin = null,
        protected ?string   $descriptionHtml = null,
        protected ?string   $giftCardTemplateSuffix = null,
        protected ?string   $handle = null,
        protected ?array    $metafields = null,
        protected ?string   $productType = null,
        protected ?bool     $requiresSellingPlan = null,
        protected ?SeoInput $seo = null,
        protected ?string   $status = null,
        protected ?array    $tags = null,
        protected ?string   $templateSuffix = null,
        protected ?string   $title = null,
        protected ?string   $vendor = null
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];

        if (!empty($this->getCategory())) {
            $data['category'] = $this->getCategory();
        }

        if (!empty($this->getCollectionsToJoin())) {
            $data['collectionsToJoin'] = $this->getCollectionsToJoin();
        }

        if (!empty($this->getDescriptionHtml())) {
            $data['descriptionHtml'] = $this->getDescriptionHtml();
        }

        if (!empty($this->getGiftCardTemplateSuffix())) {
            $data['giftCardTemplateSuffix'] = $this->getGiftCardTemplateSuffix();
        }

        if (!empty($this->getHandle())) {
            $data['handle'] = $this->getHandle();
        }

        if (!empty($this->getMetafields())) {
            $data['metafields'] = $this->getMetafields();
        }

        if (!empty($this->getProductType())) {
            $data['productType'] = $this->getProductType();
        }

        if (!empty($this->getRequiresSellingPlan())) {
            $data['requiresSellingPlan'] = $this->getRequiresSellingPlan();
        }

        if (!empty($this->getSeo())) {
            $data['seo'] = $this->getSeo()->getAsArray();
        }

        if (!empty($this->getStatus())) {
            $data['status'] = $this->getStatus();
        }

        if (!empty($this->getTags())) {
            $data['tags'] = $this->getTags();
        }

        if (!empty($this->getTemplateSuffix())) {
            $data['templateSuffix'] = $this->getTemplateSuffix();
        }

        if (!empty($this->getTitle())) {
            $data['title'] = $this->getTitle();
        }

        if (!empty($this->getVendor())) {
            $data['vendor'] = $this->getVendor();
        }

        return $data;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): void
    {
        $this->category = $category;
    }

    public function getCollectionsToJoin(): ?array
    {
        return $this->collectionsToJoin;
    }

    public function setCollectionsToJoin(?array $collectionsToJoin): void
    {
        $this->collectionsToJoin = $collectionsToJoin;
    }

    public function getDescriptionHtml(): ?string
    {
        return $this->descriptionHtml;
    }

    public function setDescriptionHtml(?string $descriptionHtml): void
    {
        $this->descriptionHtml = $descriptionHtml;
    }

    public function getGiftCardTemplateSuffix(): ?string
    {
        return $this->giftCardTemplateSuffix;
    }

    public function setGiftCardTemplateSuffix(?string $giftCardTemplateSuffix): void
    {
        $this->giftCardTemplateSuffix = $giftCardTemplateSuffix;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(?string $handle): void
    {
        $this->handle = $handle;
    }

    public function getMetafields(): ?array
    {
        return $this->metafields;
    }

    public function setMetafields(?array $metafields): void
    {
        $this->metafields = $metafields;
    }

    public function getProductType(): ?string
    {
        return $this->productType;
    }

    public function setProductType(?string $productType): void
    {
        $this->productType = $productType;
    }

    public function getRequiresSellingPlan(): ?bool
    {
        return $this->requiresSellingPlan;
    }

    public function setRequiresSellingPlan(?bool $requiresSellingPlan): void
    {
        $this->requiresSellingPlan = $requiresSellingPlan;
    }

    public function getSeo(): ?SeoInput
    {
        return $this->seo;
    }

    public function setSeo(?SeoInput $seo): void
    {
        $this->seo = $seo;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getTags(): ?array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): void
    {
        $this->tags = $tags;
    }

    public function getTemplateSuffix(): ?string
    {
        return $this->templateSuffix;
    }

    public function setTemplateSuffix(?string $templateSuffix): void
    {
        $this->templateSuffix = $templateSuffix;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getVendor(): ?string
    {
        return $this->vendor;
    }

    public function setVendor(?string $vendor): void
    {
        $this->vendor = $vendor;
    }
}
