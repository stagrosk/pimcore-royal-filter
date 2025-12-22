<?php

namespace App\GraphQL\Resolver\Wishlist;

use App\GraphQL\Resolver\AbstractResolver;
use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductWishlist;

class WishlistResolver extends AbstractResolver
{
    /**
     * Get wishlist products for a customer
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

        $wishlist = $this->getOrCreateWishlist($customerApiId);

        $products = $this->getProductsInWishlist($wishlist);

        return [
            'customerApiId' => $customerApiId,
            'products' => $products,
            'count' => count($products),
        ];
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
        $wishlist = ProductWishlist::getByCustimerApiId($customerApiId, 1);

        if (!$wishlist instanceof ProductWishlist) {
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
     * Get all products that have this wishlist in their wishlists relation
     *
     * @param \Pimcore\Model\DataObject\ProductWishlist $wishlist
     *
     * @return array
     */
    private function getProductsInWishlist(ProductWishlist $wishlist): array
    {
        $products = [];

        $productList = Product::getList();
        $productList->addConditionParam(
            'o_id IN (SELECT src_id FROM object_relations_product WHERE dest_id = ? AND fieldname = ?)',
            [$wishlist->getId(), 'wishlists']
        );

        foreach ($productList as $product) {
            $products[] = [
                'id' => $product->getId(),
                'apiId' => $product->getApiId(),
                'name' => $product->getName(),
            ];
        }

        return $products;
    }
}