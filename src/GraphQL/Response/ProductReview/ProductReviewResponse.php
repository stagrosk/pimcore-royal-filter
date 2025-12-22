<?php

namespace App\GraphQL\Response\ProductReview;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductReviewResponse extends AbstractResponse
{
    private static ?ObjectType $reviewType = null;

    public function __construct()
    {
        parent::__construct([
            'name' => 'ProductReviewResponse',
            'fields' => [
                'productApiId' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Shopify product API ID',
                ],
                'reviews' => [
                    'type' => Type::listOf(self::getReviewType()),
                    'description' => 'List of reviews for this product',
                ],
                'count' => [
                    'type' => Type::nonNull(Type::int()),
                    'description' => 'Total number of reviews',
                ],
                'averageRating' => [
                    'type' => Type::nonNull(Type::float()),
                    'description' => 'Average rating for this product',
                ],
            ],
        ]);
    }

    /**
     * Get review type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getReviewType(): ObjectType
    {
        if (self::$reviewType === null) {
            self::$reviewType = new ObjectType([
                'name' => 'ProductReviewItem',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::int()),
                        'description' => 'Review ID',
                    ],
                    'rating' => [
                        'type' => Type::float(),
                        'description' => 'Rating (1-5)',
                    ],
                    'content' => [
                        'type' => Type::string(),
                        'description' => 'Review content/text',
                    ],
                    'customerApiId' => [
                        'type' => Type::string(),
                        'description' => 'Shopify customer API ID',
                    ],
                    'createdAt' => [
                        'type' => Type::int(),
                        'description' => 'Creation timestamp',
                    ],
                ],
            ]);
        }

        return self::$reviewType;
    }
}