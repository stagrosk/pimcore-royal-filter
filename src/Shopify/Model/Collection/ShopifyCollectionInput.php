<?php

namespace App\Shopify\Model\Collection;

use App\Shopify\Model\Base\ShopifyImageInput;
use App\Shopify\Model\Base\ShopifyMetafieldInput;
use App\Shopify\Model\Base\ShopifySeoInput;
use App\Shopify\Model\IShopifyModel;

class ShopifyCollectionInput implements IShopifyModel
{
    /**
     * @param string|null $id
     * @param string|null $title
     * @param string|null $descriptionHtml
     * @param array|null $products
     * @param string|null $handle
     * @param \App\Shopify\Model\Base\ShopifyImageInput|null $image
     * @param \App\Shopify\Model\Base\ShopifyMetafieldInput|null $metafields
     * @param bool $redirectNewHandle
     * @param \App\Shopify\Model\Collection\ShopifyCollectionRuleSetInput|null $rules
     * @param \App\Shopify\Model\Base\ShopifySeoInput|null $seo
     * @param \App\Shopify\Model\Collection\ShopifyCollectionSortOrder|null $sortOrder
     */
    public function __construct(
        private ?string                        $id = null,
        private ?string                        $title = null,
        private ?string                        $descriptionHtml = null,
        private ?array                         $products = [],
        private ?string                        $handle = null,
        private ?ShopifyImageInput             $image = null,
        private ?ShopifyMetafieldInput         $metafields = null,
        private bool                           $redirectNewHandle = false,
        private ?ShopifyCollectionRuleSetInput $rules = null,
        private ?ShopifySeoInput               $seo = null,
        private ?ShopifyCollectionSortOrder    $sortOrder = null,
    ) {
        $this->sortOrder = new ShopifyCollectionSortOrder();
    }

    public function getAsArray(): array
    {
        $data = [
            'title' => $this->getTitle(),
            'descriptionHtml' => $this->getDescriptionHtml(),
            'handle' => $this->getHandle(),
            'sortOrder' => $this->getSortOrder()->getSorting(),
            'redirectNewHandle' => $this->isRedirectNewHandle(),
        ];

        if (!empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (!empty($this->getImage())) {
            $data['image'] = $this->getImage()->getAsArray();
        }

        if (!empty($this->getMetafields())) {
            $data['metafields'] = $this->getMetafields()->getAsArray();
        }

        //  only for collectionCreate and without rules
        if (!empty($this->getProducts()) && empty($this->getRules())) {
            $data['products'] = $this->getProducts();
        }

        if (!empty($this->getSeo())) {
            $data['seo'] = $this->getSeo()->getAsArray();
        }

        if (!empty($this->getRules())) {
            // ruleset / skipped
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getDescriptionHtml(): ?string
    {
        return $this->descriptionHtml;
    }

    public function setDescriptionHtml(?string $descriptionHtml): void
    {
        $this->descriptionHtml = $descriptionHtml;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(?array $products): void
    {
        $this->products = $products;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(?string $handle): void
    {
        $this->handle = $handle;
    }

    public function getImage(): ?ShopifyImageInput
    {
        return $this->image;
    }

    public function setImage(?ShopifyImageInput $image): void
    {
        $this->image = $image;
    }

    public function getMetafields(): ?ShopifyMetafieldInput
    {
        return $this->metafields;
    }

    public function setMetafields(?ShopifyMetafieldInput $metafields): void
    {
        $this->metafields = $metafields;
    }

    public function isRedirectNewHandle(): bool
    {
        return $this->redirectNewHandle;
    }

    /**
     * Indicates whether a redirect is required after a new handle has been provided. If true, then the old handle is redirected to the new one automatically.
     *
     * @param bool $redirectNewHandle
     *
     * @return void
     */
    public function setRedirectNewHandle(bool $redirectNewHandle): void
    {
        $this->redirectNewHandle = $redirectNewHandle;
    }

    public function getRules(): ?ShopifyCollectionRuleSetInput
    {
        return $this->rules;
    }

    public function setRules(?ShopifyCollectionRuleSetInput $rules): void
    {
        $this->rules = $rules;
    }

    public function getSeo(): ?ShopifySeoInput
    {
        return $this->seo;
    }

    public function setSeo(?ShopifySeoInput $seo): void
    {
        $this->seo = $seo;
    }

    public function getSortOrder(): ?ShopifyCollectionSortOrder
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?ShopifyCollectionSortOrder $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }
}
