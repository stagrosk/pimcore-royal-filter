<?php

namespace App\GraphQL\Resolver\ProductReview;

use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\ProductReview;
use Pimcore\Model\DataObject\Service;

class ProductReviewMutationResolver
{
    /**
     * Add a product review
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function addReview($source, $args, $context, ResolveInfo $info): array
    {
        $productApiId = $args['productApiId'];
        $customerApiId = $args['customerApiId'];
        $rating = $args['rating'];
        $content = $args['content'] ?? '';

        try {
            $product = $this->getProductByApiId($productApiId);

            if (!$product) {
                return [
                    'success' => false,
                    'message' => 'Product not found',
                    'reviewId' => null,
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Check if customer already reviewed this product
            $existingReview = $this->getExistingReview($product, $customerApiId);
            if ($existingReview) {
                return [
                    'success' => false,
                    'message' => 'Customer already reviewed this product',
                    'reviewId' => $existingReview->getId(),
                    'productApiId' => $productApiId,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Create new review
            $review = new ProductReview();
            $review->setKey('review_' . $product->getId() . '_' . md5($customerApiId));
            $review->setParent(Service::createFolderByPath('/reviews'));
            $review->setProduct($product);
            $review->setCustimerApiId($customerApiId);
            $review->setRating((float) $rating);
            $review->setContent($content);
            $review->setPublished(true);
            $review->save();

            return [
                'success' => true,
                'message' => 'Review added successfully',
                'reviewId' => $review->getId(),
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'reviewId' => null,
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        }
    }

    /**
     * Update a product review
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function updateReview($source, $args, $context, ResolveInfo $info): array
    {
        $reviewId = $args['reviewId'];
        $customerApiId = $args['customerApiId'];
        $rating = $args['rating'] ?? null;
        $content = $args['content'] ?? null;

        try {
            $review = ProductReview::getById($reviewId);

            if (!$review) {
                return [
                    'success' => false,
                    'message' => 'Review not found',
                    'reviewId' => $reviewId,
                    'productApiId' => null,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Verify ownership
            if ($review->getCustimerApiId() !== $customerApiId) {
                return [
                    'success' => false,
                    'message' => 'Not authorized to update this review',
                    'reviewId' => $reviewId,
                    'productApiId' => $review->getProduct()?->getApiId(),
                    'customerApiId' => $customerApiId,
                ];
            }

            if ($rating !== null) {
                $review->setRating((float) $rating);
            }

            if ($content !== null) {
                $review->setContent($content);
            }

            $review->save();

            return [
                'success' => true,
                'message' => 'Review updated successfully',
                'reviewId' => $review->getId(),
                'productApiId' => $review->getProduct()?->getApiId(),
                'customerApiId' => $customerApiId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'reviewId' => $reviewId,
                'productApiId' => null,
                'customerApiId' => $customerApiId,
            ];
        }
    }

    /**
     * Delete a product review
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function deleteReview($source, $args, $context, ResolveInfo $info): array
    {
        $reviewId = $args['reviewId'];
        $customerApiId = $args['customerApiId'];

        try {
            $review = ProductReview::getById($reviewId);

            if (!$review) {
                return [
                    'success' => false,
                    'message' => 'Review not found',
                    'reviewId' => $reviewId,
                    'productApiId' => null,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Verify ownership
            if ($review->getCustimerApiId() !== $customerApiId) {
                return [
                    'success' => false,
                    'message' => 'Not authorized to delete this review',
                    'reviewId' => $reviewId,
                    'productApiId' => $review->getProduct()?->getApiId(),
                    'customerApiId' => $customerApiId,
                ];
            }

            $productApiId = $review->getProduct()?->getApiId();
            $review->delete();

            return [
                'success' => true,
                'message' => 'Review deleted successfully',
                'reviewId' => $reviewId,
                'productApiId' => $productApiId,
                'customerApiId' => $customerApiId,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'reviewId' => $reviewId,
                'productApiId' => null,
                'customerApiId' => $customerApiId,
            ];
        }
    }

    /**
     * Get existing review for product by customer
     *
     * @param \Pimcore\Model\DataObject\Product $product
     * @param string $customerApiId
     *
     * @return \Pimcore\Model\DataObject\ProductReview|null
     */
    private function getExistingReview(Product $product, string $customerApiId): ?ProductReview
    {
        $reviewList = ProductReview::getList();
        $reviewList->addConditionParam('product__id = ?', $product->getId());
        $reviewList->addConditionParam('custimerApiId = ?', $customerApiId);
        $reviewList->setLimit(1);

        $review = $reviewList->current();

        return $review instanceof ProductReview ? $review : null;
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