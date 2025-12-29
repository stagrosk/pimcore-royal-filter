<?php

namespace App\GraphQL\Response\Review;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ReviewResponse extends AbstractResponse
{
    private static ?ObjectType $replyType = null;

    private static ?ObjectType $reviewItemType = null;

    public function __construct()
    {
        parent::__construct([
            'name' => 'ReviewResponse',
            'fields' => [
                'productApiId' => [
                    'type' => Type::string(),
                    'description' => 'Product API ID (null for shop reviews)',
                ],
                'reviews' => [
                    'type' => Type::listOf(self::getReviewItemType()),
                    'description' => 'List of reviews',
                ],
                'count' => [
                    'type' => Type::nonNull(Type::int()),
                    'description' => 'Total number of reviews',
                ],
                'averageRating' => [
                    'type' => Type::nonNull(Type::float()),
                    'description' => 'Average rating',
                ],
            ],
        ]);
    }

    /**
     * Get review item type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getReviewItemType(): ObjectType
    {
        if (self::$reviewItemType === null) {
            self::$reviewItemType = new ObjectType([
                'name' => 'ReviewItem',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::int()),
                        'description' => 'Review ID',
                    ],
                    'rating' => [
                        'type' => Type::nonNull(Type::float()),
                        'description' => 'Rating (1-5)',
                    ],
                    'content' => [
                        'type' => Type::string(),
                        'description' => 'Review content',
                    ],
                    'customerApiId' => [
                        'type' => Type::nonNull(Type::string()),
                        'description' => 'Customer API ID',
                    ],
                    'firstName' => [
                        'type' => Type::string(),
                        'description' => 'Customer first name',
                    ],
                    'lastName' => [
                        'type' => Type::string(),
                        'description' => 'Customer last name',
                    ],
                    'createdAt' => [
                        'type' => Type::int(),
                        'description' => 'Created timestamp',
                    ],
                    'replys' => [
                        'type' => Type::listOf(self::getReplyType()),
                        'description' => 'Replies to this review',
                    ],
                ],
            ]);
        }

        return self::$reviewItemType;
    }

    /**
     * Get reply type (singleton)
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private static function getReplyType(): ObjectType
    {
        if (self::$replyType === null) {
            self::$replyType = new ObjectType([
                'name' => 'ReviewReply',
                'fields' => [
                    'content' => [
                        'type' => Type::string(),
                        'description' => 'Reply content',
                    ],
                    'customerApiId' => [
                        'type' => Type::string(),
                        'description' => 'Customer API ID who replied',
                    ],
                    'firstName' => [
                        'type' => Type::string(),
                        'description' => 'First name of person who replied',
                    ],
                    'lastName' => [
                        'type' => Type::string(),
                        'description' => 'Last name of person who replied',
                    ],
                    'createdAt' => [
                        'type' => Type::int(),
                        'description' => 'Reply created timestamp',
                    ],
                ],
            ]);
        }

        return self::$replyType;
    }
}