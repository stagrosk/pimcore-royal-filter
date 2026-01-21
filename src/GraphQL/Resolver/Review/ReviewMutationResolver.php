<?php

namespace App\GraphQL\Resolver\Review;

use Carbon\Carbon;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\Data\BlockElement;
use Pimcore\Model\DataObject\Product;
use Pimcore\Model\DataObject\Review;
use Pimcore\Model\DataObject\Service;

class ReviewMutationResolver
{
    /**
     * Add a review (product or shop)
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
        $productApiId = $args['productApiId'] ?? null;
        $customerApiId = $args['customerApiId'];
        $firstName = $args['firstName'] ?? '';
        $lastName = $args['lastName'] ?? '';
        $rating = $args['rating'];
        $content = $args['content'] ?? '';

        try {
            $product = null;
            if ($productApiId) {
                $product = $this->getProductByApiId($productApiId);
                if (!$product) {
                    return $this->errorResponse('Product not found', $productApiId, $customerApiId);
                }

                // Check if customer already reviewed this product
                $existingReview = $this->getExistingProductReview($product, $customerApiId);
                if ($existingReview) {
                    return [
                        'success' => false,
                        'message' => 'Customer already reviewed this product',
                        'reviewId' => $existingReview->getId(),
                        'productApiId' => $productApiId,
                        'customerApiId' => $customerApiId,
                    ];
                }
            } else {
                // Check if customer already reviewed the shop
                $existingReview = $this->getExistingShopReview($customerApiId);
                if ($existingReview) {
                    return [
                        'success' => false,
                        'message' => 'Customer already reviewed this shop',
                        'reviewId' => $existingReview->getId(),
                        'productApiId' => null,
                        'customerApiId' => $customerApiId,
                    ];
                }
            }

            // Create new review with unique key (microtime for uniqueness)
            $review = new Review();
            $uniqueId = str_replace('.', '', microtime(true)) . '_' . bin2hex(random_bytes(4));
            $review->setKey('review_' . $uniqueId);

            $dateFolder = date('Y-m-d');
            if ($product) {
                $reviewsFolder = Service::createFolderByPath($product->getFullPath() . '/Reviews/' . $dateFolder);
            } else {
                $reviewsFolder = Service::createFolderByPath('/ShopReviews/' . $dateFolder);
            }

            $review->setParent($reviewsFolder);
            $review->setProduct($product);
            $review->setCustomerApiId($customerApiId);
            $review->setFirstName($firstName);
            $review->setLastName($lastName);
            $review->setRating((float) $rating);
            $review->setContent($content);
            $review->setCreatedDateTime(Carbon::now());
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
            return $this->errorResponse('Error: ' . $e->getMessage(), $productApiId, $customerApiId);
        }
    }

    /**
     * Update a review
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
            $review = Review::getById($reviewId);

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
            if ($review->getCustomerApiId() !== $customerApiId) {
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
     * Delete a review
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
            $review = Review::getById($reviewId);

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
            if ($review->getCustomerApiId() !== $customerApiId) {
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
     * Add a reply to a review
     *
     * @param mixed $source
     * @param mixed $args
     * @param mixed $context
     * @param \GraphQL\Type\Definition\ResolveInfo $info
     *
     * @return array
     */
    public function addReply($source, $args, $context, ResolveInfo $info): array
    {
        $reviewId = $args['reviewId'];
        $customerApiId = $args['customerApiId'];
        $firstName = $args['firstName'] ?? '';
        $lastName = $args['lastName'] ?? '';
        $content = $args['content'];

        try {
            $review = Review::getById($reviewId);

            if (!$review) {
                return [
                    'success' => false,
                    'message' => 'Review not found',
                    'reviewId' => $reviewId,
                    'productApiId' => null,
                    'customerApiId' => $customerApiId,
                ];
            }

            // Get existing replies
            $replys = $review->getReplys() ?? [];

            // Add new reply
            $replys[] = [
                'replyContent' => new BlockElement('replyContent', 'input', $content),
                'replyDateTime' => new BlockElement('replyDateTime', 'datetime', Carbon::now()),
                'replyCustomerApiId' => new BlockElement('replyCustomerApiId', 'input', $customerApiId),
                'replyFirstName' => new BlockElement('replyFirstName', 'input', $firstName),
                'replyLastName' => new BlockElement('replyLastName', 'input', $lastName),
            ];

            $review->setReplys($replys);
            $review->save();

            return [
                'success' => true,
                'message' => 'Reply added successfully',
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
     * Get existing product review for customer
     *
     * @param \Pimcore\Model\DataObject\Product $product
     * @param string $customerApiId
     *
     * @return \Pimcore\Model\DataObject\Review|null
     */
    private function getExistingProductReview(Product $product, string $customerApiId): ?Review
    {
        $reviewList = Review::getList();
        $reviewList->addConditionParam('product__id = ?', $product->getId());
        $reviewList->addConditionParam('customerApiId = ?', $customerApiId);
        $reviewList->setLimit(1);

        $review = $reviewList->current();

        return $review instanceof Review ? $review : null;
    }

    /**
     * Get existing shop review for customer (review without product)
     *
     * @param string $customerApiId
     *
     * @return \Pimcore\Model\DataObject\Review|null
     */
    private function getExistingShopReview(string $customerApiId): ?Review
    {
        $reviewList = Review::getList();
        $reviewList->addConditionParam('product__id IS NULL');
        $reviewList->addConditionParam('customerApiId = ?', $customerApiId);
        $reviewList->setLimit(1);

        $review = $reviewList->current();

        return $review instanceof Review ? $review : null;
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

    /**
     * Create error response
     *
     * @param string $message
     * @param string|null $productApiId
     * @param string $customerApiId
     *
     * @return array
     */
    private function errorResponse(string $message, ?string $productApiId, string $customerApiId): array
    {
        return [
            'success' => false,
            'message' => $message,
            'reviewId' => null,
            'productApiId' => $productApiId,
            'customerApiId' => $customerApiId,
        ];
    }
}
