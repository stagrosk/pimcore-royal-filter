<?php

namespace App\Shopify\Model\Collection;

use App\Shopify\Model\IShopifyModel;
use App\Shopify\Model\Media\CreateMediaInput;
use App\Shopify\Model\Media\ImageInput;
use App\Shopify\Model\Metafields\MetafieldInput;
use App\Shopify\Model\Seo\SeoInput;

class CollectionInput implements IShopifyModel
{
    /**
     * @param string|null $id
     * @param string|null $title
     * @param string|null $descriptionHtml
     * @param array|null $products
     * @param string|null $handle
     * @param \App\Shopify\Model\Media\ImageInput|null $image
     * @param array|null $metafields
     * @param bool $redirectNewHandle
     * @param \App\Shopify\Model\Collection\CollectionRuleSetInput|null $rules
     * @param \App\Shopify\Model\Seo\SeoInput|null $seo
     * @param \App\Shopify\Model\Collection\CollectionSortOrder|null $sortOrder
     */
    public function __construct(
        private ?string                 $id = null,
        private ?string                 $title = null,
        private ?string                 $descriptionHtml = null,
        private ?array                  $products = [],
        private ?string                 $handle = null,
        private ?ImageInput             $image = null,
        private ?array                  $metafields = null,
        private bool                    $redirectNewHandle = false,
        private ?CollectionRuleSetInput $rules = null,
        private ?SeoInput               $seo = null,
        private ?CollectionSortOrder    $sortOrder = null,
    ) {
        $this->sortOrder = new CollectionSortOrder();
    }

    public function getAsArray(): array
    {
        $data = [
            'title' => $this->getTitle(),
            'descriptionHtml' => $this->getDescriptionHtml(),
            'handle' => $this->getHandle(),
            'sortOrder' => $this->getSortOrder()?->getSorting(),
            'redirectNewHandle' => $this->isRedirectNewHandle(),
        ];

        if (!empty($this->getId())) {
            $data['id'] = $this->getId();
        }

        if (!empty($this->getImage())) {
            $data['image'] = $this->getImage()->getAsArray();
        }

        if (!empty($this->getMetafields())) {
            $data['metafields'] = $this->getMetafields();
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

    public function getImage(): ?ImageInput
    {
        return $this->image;
    }

    public function setImage(?ImageInput $image): void
    {
        $this->image = $image;
    }

    public function getMetafields(): ?array
    {
        return $this->metafields;
    }

    public function setMetafields(?array $metafields): void
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

    public function getRules(): ?CollectionRuleSetInput
    {
        return $this->rules;
    }

    public function setRules(?CollectionRuleSetInput $rules): void
    {
        $this->rules = $rules;
    }

    public function getSeo(): ?SeoInput
    {
        return $this->seo;
    }

    public function setSeo(?SeoInput $seo): void
    {
        $this->seo = $seo;
    }

    public function getSortOrder(): ?CollectionSortOrder
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?CollectionSortOrder $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }
}
