<?php

namespace App\GraphQL\Resolver\Wishlist;

use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\ShopifyCustomerWishlist;

class WishlistResolver extends AbstractResolver
{
    /**
     * Get wishlist for customer
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function resolve($source, $args, $context, ResolveInfo $info): array
    {
        $customerApiId = $args['customerApiId'];
        $productApiId = $args['productApiId'] ?? null;

        $wishlist = $this->getWishlist($customerApiId);

        if (!$wishlist) {
            return [
                'customerApiId' => $customerApiId,
                'productApiIds' => [],
                'count' => 0,
                'hasProduct' => false,
            ];
        }

        $products = $wishlist->getProducts() ?? [];
        $productApiIds = array_map(fn($product) => $product->getApiId(), $products);
        $productApiIds = array_filter($productApiIds); // Remove nulls

        // Check if specific product is in wishlist
        $hasProduct = false;
        if ($productApiId) {
            $hasProduct = in_array($productApiId, $productApiIds, true);
        }

        return [
            'customerApiId' => $customerApiId,
            'productApiIds' => array_values($productApiIds),
            'count' => count($productApiIds),
            'hasProduct' => $hasProduct,
        ];
    }

    /**
     * Get wishlist for customer
     *
     * @param string $customerApiId
     *
     * @return \Pimcore\Model\DataObject\ShopifyCustomerWishlist|null
     */
    private function getWishlist(string $customerApiId): ?ShopifyCustomerWishlist
    {
        $wishlist = ShopifyCustomerWishlist::getByCustimerApiId($customerApiId, 1);

        return $wishlist instanceof ShopifyCustomerWishlist ? $wishlist : null;
    }
}