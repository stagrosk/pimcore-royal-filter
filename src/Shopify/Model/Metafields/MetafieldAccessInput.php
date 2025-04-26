<?php

namespace App\Shopify\Model\Metafields;

use App\Shopify\Model\IShopifyModel;

class MetafieldAccessInput implements IShopifyModel
{
    /**
     * @param \App\Shopify\Model\Metafields\MetafieldAdminAccessInputEnum|null $admin
     * @param \App\Shopify\Model\Metafields\MetafieldCustomerAccountAccessInputEnum|null $customerAccount
     * @param \App\Shopify\Model\Metafields\MetafieldStorefrontAccessInputEnum|null $storefront
     */
    public function __construct(
        public ?MetafieldAdminAccessInputEnum $admin = null,
        public ?MetafieldCustomerAccountAccessInputEnum $customerAccount = null,
        public ?MetafieldStorefrontAccessInputEnum $storefront = null,
    ) {
    }

    /**
     * @return array
     */
    public function getAsArray(): array
    {
        $data = [];
//        if ($this->getAdmin() !== null) {
//            $data['admin'] = $this->getAdmin()->value;
//        }
//        if ($this->getCustomerAccount() !== null) {
//            $data['customerAccount'] = $this->getCustomerAccount()->value;
//        }
        if ($this->getStorefront() !== null) {
            $data['storefront'] = $this->getStorefront()->value;
        }
        return $data;
    }

    public function getAdmin(): ?MetafieldAdminAccessInputEnum
    {
        return $this->admin;
    }

    public function setAdmin(?MetafieldAdminAccessInputEnum $admin): void
    {
        $this->admin = $admin;
    }

    public function getCustomerAccount(): ?MetafieldCustomerAccountAccessInputEnum
    {
        return $this->customerAccount;
    }

    public function setCustomerAccount(?MetafieldCustomerAccountAccessInputEnum $customerAccount): void
    {
        $this->customerAccount = $customerAccount;
    }

    public function getStorefront(): ?MetafieldStorefrontAccessInputEnum
    {
        return $this->storefront;
    }

    public function setStorefront(?MetafieldStorefrontAccessInputEnum $storefront): void
    {
        $this->storefront = $storefront;
    }
}
