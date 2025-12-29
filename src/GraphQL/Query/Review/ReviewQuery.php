<?php

namespace App\GraphQL\Query\Review;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Review\ReviewResolver;
use App\GraphQL\Response\Review\ReviewResponse;
use App\GraphQL\Type\Arguments\Review\ReviewArgs;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class ReviewQuery extends AbstractQuery
{
    private ReviewResponse $response;

    private ReviewResolver $resolver;

    /**
     * @param \App\GraphQL\Response\Review\ReviewResponse $response
     * @param \App\GraphQL\Resolver\Review\ReviewResolver $resolver
     */
    public function __construct(ReviewResponse $response, ReviewResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => '\App\GraphQL\Response\Review\ReviewResponse', 'args' => 'array', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => ReviewArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'getReviews';
    }
}