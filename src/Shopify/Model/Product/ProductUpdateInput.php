<?php

namespace App\Shopify\Model\Product;

use App\Shopify\Model\Seo\SeoInput;

class ProductUpdateInput extends ProductBaseInput {
    /**
     * @param string|null $id
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
     * @param array|null $collectionsToLeave
     * @param bool|null $deleteConflictingConstrainedMetafields
     * @param bool|null $redirectNewHandle
     */
    public function __construct(
        private ?string $id = null,
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
        private ?array  $collectionsToLeave = null,
        private ?bool   $deleteConflictingConstrainedMetafields = false,
        private ?bool   $redirectNewHandle = null,
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

        if (!empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (!empty($this->getCollectionsToLeave())) {
            $data['collectionsToLeave'] = $this->getCollectionsToLeave();
        }

        if (!empty($this->getDeleteConflictingConstrainedMetafields())) {
            $data['deleteConflictingConstrainedMetafields'] = $this->getDeleteConflictingConstrainedMetafields();
        }

        if (!empty($this->getRedirectNewHandle())) {
            $data['redirectNewHandle'] = $this->getRedirectNewHandle();
        }

        return $data;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getCollectionsToLeave(): ?array
    {
        return $this->collectionsToLeave;
    }

    public function setCollectionsToLeave(?array $collectionsToLeave): void
    {
        $this->collectionsToLeave = $collectionsToLeave;
    }

    public function getDeleteConflictingConstrainedMetafields(): ?bool
    {
        return $this->deleteConflictingConstrainedMetafields;
    }

    public function setDeleteConflictingConstrainedMetafields(?bool $deleteConflictingConstrainedMetafields): void
    {
        $this->deleteConflictingConstrainedMetafields = $deleteConflictingConstrainedMetafields;
    }

    public function getRedirectNewHandle(): ?bool
    {
        return $this->redirectNewHandle;
    }

    public function setRedirectNewHandle(?bool $redirectNewHandle): void
    {
        $this->redirectNewHandle = $redirectNewHandle;
    }
}
