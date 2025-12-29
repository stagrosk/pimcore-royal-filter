<?php

namespace App\GraphQL\Resolver\Wishlist;

use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Service;
use Pimcore\Model\DataObject\ShopifyCustomerWishlist;

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
            $product = $this->getProductByApiId($productApiId);
            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Product not found',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            $wishlist = $this->getOrCreateWishlist($customerApiId);
            $currentProducts = $wishlist->getProducts() ?? [];
            $productIds = array_map(fn($p) => $p->getId(), $currentProducts);

            // Check if product is already in wishlist
            if (in_array($product->getId(), $productIds, true)) {
                return [
                    'success' => false,
                    'message' => 'Product already in wishlist',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Add product to wishlist
            $currentProducts[] = $product;
            $wishlist->setProducts($currentProducts);
            $wishlist->save();

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
            $product = $this->getProductByApiId($productApiId);
            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Product not found',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            $wishlist = $this->getWishlist($customerApiId);
            if (!$wishlist) {
                return [
                    'success' => false,
                    'message' => 'Wishlist not found',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            $currentProducts = $wishlist->getProducts() ?? [];
            $filteredProducts = array_filter(
                $currentProducts,
                fn($p) => $p->getId() !== $product->getId()
            );

            if (count($filteredProducts) === count($currentProducts)) {
                return [
                    'success' => false,
                    'message' => 'Product was not in wishlist',
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            $wishlist->setProducts(array_values($filteredProducts));
            $wishlist->save();

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
     * @throws \Exception
     * @return \Pimcore\Model\DataObject\ShopifyCustomerWishlist
     */
    private function getOrCreateWishlist(string $customerApiId): ShopifyCustomerWishlist
    {
        $wishlist = $this->getWishlist($customerApiId);

        if (!$wishlist) {
            $wishlist = new ShopifyCustomerWishlist();

            $customerApiKeyFormatted = Service::getValidKey($customerApiId, 'object');
            $folder = Service::createFolderByPath('Shopify/Customer/Wishlists');
            $wishlist->setParent($folder);
            $wishlist->setKey($customerApiKeyFormatted);
            $wishlist->setCustimerApiId($customerApiId);
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
     * @return \Pimcore\Model\DataObject\ShopifyCustomerWishlist|null
     */
    private function getWishlist(string $customerApiId): ?ShopifyCustomerWishlist
    {
        $wishlist = ShopifyCustomerWishlist::getByCustimerApiId($customerApiId, 1);

        return $wishlist instanceof ShopifyCustomerWishlist ? $wishlist : null;
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
