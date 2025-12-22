<?php

namespace App\GraphQL\Query\ProductReview;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\ProductReview\ProductReviewResolver;
use App\GraphQL\Response\ProductReview\ProductReviewResponse;
use App\GraphQL\Type\Arguments\ProductReview\ProductReviewArgs;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class ProductReviewQuery extends AbstractQuery
{
    private ProductReviewResponse $response;

    private ProductReviewResolver $resolver;

    /**
     * @param \App\GraphQL\Response\ProductReview\ProductReviewResponse $response
     * @param \App\GraphQL\Resolver\ProductReview\ProductReviewResolver $resolver
     */
    public function __construct(ProductReviewResponse $response, ProductReviewResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => "\App\GraphQL\Response\ProductReview\ProductReviewResponse", 'args' => 'array', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => ProductReviewArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'getProductReviews';
    }
}