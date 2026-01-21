<?php

namespace App\GraphQL\Resolver\Review;

use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Review;

class ReviewResolver extends AbstractResolver
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
        $productApiId = $args['productApiId'] ?? null;

        if ($productApiId) {
            return $this->getProductReviews($productApiId);
        }

        return $this->getShopReviews();
    }

    /**
     * Get reviews for a specific product
     *
     * @param string $productApiId
     *
     * @return array
     */
    private function getProductReviews(string $productApiId): array
    {
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
     * Get shop reviews (reviews without product)
     *
     * @return array
     */
    private function getShopReviews(): array
    {
        $reviewList = Review::getList();
        $reviewList->addConditionParam('product__id IS NULL');
        $reviewList->setOrderKey('createdDateTime');
        $reviewList->setOrder('DESC');

        $reviews = [];
        foreach ($reviewList as $review) {
            $reviews[] = $this->formatReview($review);
        }

        $averageRating = $this->calculateAverageRating($reviews);

        return [
            'productApiId' => null,
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
     * @return array
     */
    private function getReviewsForProduct(Product $product): array
    {
        $reviews = [];

        $reviewList = Review::getList();
        $reviewList->addConditionParam('product__id = ?', $product->getId());
        $reviewList->setOrderKey('createdDateTime');
        $reviewList->setOrder('DESC');

        foreach ($reviewList as $review) {
            $reviews[] = $this->formatReview($review);
        }

        return $reviews;
    }

    /**
     * Format review for response
     *
     * @param \Pimcore\Model\DataObject\Review $review
     *
     * @return array
     */
    private function formatReview(Review $review): array
    {
        $replys = [];
        $replyBlocks = $review->getReplys() ?? [];

        foreach ($replyBlocks as $replyBlock) {
            $replys[] = [
                'content' => $replyBlock['replyContent']?->getData() ?? '',
                'customerApiId' => $replyBlock['replyCustomerApiId']?->getData() ?? '',
                'firstName' => $replyBlock['replyFirstName']?->getData() ?? '',
                'lastName' => $replyBlock['replyLastName']?->getData() ?? '',
                'createdAt' => $replyBlock['replyDateTime']?->getData()?->getTimestamp() ?? null,
            ];
        }

        return [
            'id' => $review->getId(),
            'rating' => $review->getRating(),
            'content' => $review->getContent(),
            'customerApiId' => $review->getCustomerApiId(),
            'firstName' => $review->getFirstName(),
            'lastName' => $review->getLastName(),
            'createdAt' => $review->getCreatedDateTime()?->getTimestamp(),
            'replys' => $replys,
        ];
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
     * Get product by API ID
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
