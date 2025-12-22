<?php

namespace App\GraphQL\Resolver\ProductReview;

use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductReview;

class ProductReviewResolver extends AbstractResolver
{
    /**
     * Get reviews for a product
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
        $productApiId = $args['productApiId'];

        $product = $this->getProductByApiId($productApiId);

        if (!$product) {
            return [
                'productApiId' => $productApiId,
                'reviews' => [],
                'count' => 0,
                'averageRating' => 0,
            ];
        }

        $reviews = $this->getReviewsForProduct($product);
        $averageRating = $this->calculateAverageRating($reviews);

        return [
            'productApiId' => $productApiId,
            'reviews' => $reviews,
            'count' => count($reviews),
            'averageRating' => $averageRating,
        ];
    }

    /**
     * Get all reviews for a product
     *
     * @param \Pimcore\Model\DataObject\Product $product
     *
     * @throws \Exception
     * @return array
     */
    private function getReviewsForProduct(Product $product): array
    {
        $reviews = [];

        $reviewList = ProductReview::getList();
        $reviewList->addConditionParam('product__id = ?', $product->getId());
        $reviewList->setOrderKey('o_creationDate');
        $reviewList->setOrder('DESC');

        foreach ($reviewList as $review) {
            $reviews[] = [
                'id' => $review->getId(),
                'rating' => $review->getRating(),
                'content' => $review->getContent(),
                'customerApiId' => $review->getCustimerApiId(),
                'createdAt' => $review->getCreationDate(),
            ];
        }

        return $reviews;
    }

    /**
     * Calculate average rating from reviews
     *
     * @param array $reviews
     *
     * @return float
     */
    private function calculateAverageRating(array $reviews): float
    {
        if (empty($reviews)) {
            return 0.0;
        }

        $total = array_sum(array_column($reviews, 'rating'));

        return round($total / count($reviews), 1);
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
