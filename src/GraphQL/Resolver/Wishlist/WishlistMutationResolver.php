<?php

namespace App\GraphQL\Resolver\Wishlist;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductWishlist;

class WishlistMutationResolver
{
    /**
     * Add product to wishlist
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function addToWishlist($source, $args, $context, ResolveInfo $info): array
    {
        $customerApiId = $args['customerApiId'];
        $productApiId = $args['productApiId'];

        try {
            $wishlist = $this->getOrCreateWishlist($customerApiId);
            $product = $this->getProductByApiId($productApiId);

            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Product not found',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Check if wishlist is already in product's wishlists
            $currentWishlists = $product->getWishlists() ?? [];
            $wishlistIds = array_map(fn($w) => $w->getId(), $currentWishlists);

            if (in_array($wishlist->getId(), $wishlistIds, true)) {
                return [
                    'success' => true,
                    'message' => 'Product already in wishlist',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Add wishlist to product
            $currentWishlists[] = $wishlist;
            $product->setWishlists($currentWishlists);
            $product->save();

            return [
                'success' => true,
                'message' => 'Product added to wishlist',
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        }
    }

    /**
     * Remove product from wishlist
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function removeFromWishlist($source, $args, $context, ResolveInfo $info): array
    {
        $customerApiId = $args['customerApiId'];
        $productApiId = $args['productApiId'];

        try {
            $wishlist = $this->getWishlist($customerApiId);

            if (!$wishlist) {
                return [
                    'success' => false,
                    'message' => 'Wishlist not found',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            $product = $this->getProductByApiId($productApiId);

            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Product not found',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Remove wishlist from product's wishlists
            $currentWishlists = $product->getWishlists() ?? [];
            $filteredWishlists = array_filter(
                $currentWishlists,
                fn($w) => $w->getId() !== $wishlist->getId()
            );

            if (count($filteredWishlists) === count($currentWishlists)) {
                return [
                    'success' => true,
                    'message' => 'Product was not in wishlist',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            $product->setWishlists(array_values($filteredWishlists));
            $product->save();

            return [
                'success' => true,
                'message' => 'Product removed from wishlist',
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        }
    }

    /**
     * Get or create wishlist for customer
     *
     * @param string $customerApiId
     *
     * @return \Pimcore\Model\DataObject\ProductWishlist
     */
    private function getOrCreateWishlist(string $customerApiId): ProductWishlist
    {
        $wishlist = $this->getWishlist($customerApiId);

        if (!$wishlist) {
            $wishlist = new ProductWishlist();
            $wishlist->setKey('wishlist_' . md5($customerApiId));
            $wishlist->setParent(\Pimcore\Model\DataObject\Service::createFolderByPath('/wishlists'));
            $wishlist->setCustimerApiId($customerApiId);
            $wishlist->setDateTime(Carbon::now());
            $wishlist->setPublished(true);
            $wishlist->save();
        }

        return $wishlist;
    }

    /**
     * Get wishlist for customer
     *
     * @param string $customerApiId
     *
     * @return \Pimcore\Model\DataObject\ProductWishlist|null
     */
    private function getWishlist(string $customerApiId): ?ProductWishlist
    {
        $wishlist = ProductWishlist::getByCustimerApiId($customerApiId, 1);

        return $wishlist instanceof ProductWishlist ? $wishlist : null;
    }

    /**
     * Get product by Shopify API ID
     *
     * @param string $apiId
     *
     * @return \Pimcore\Model\DataObject\Product|null
     */
    private function getProductByApiId(string $apiId): ?Product
    {
        $product = Product::getByApiId($apiId, 1);

        return $product instanceof Product ? $product : null;
    }
}