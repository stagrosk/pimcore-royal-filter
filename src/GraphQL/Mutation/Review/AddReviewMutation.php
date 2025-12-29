<?php

namespace App\GraphQL\Mutation\Review;

use App\GraphQL\Mutation\AbstractMutation;
use App\GraphQL\Resolver\Review\ReviewMutationResolver;
use App\GraphQL\Response\Review\ReviewMutationResponse;
use App\GraphQL\Type\Arguments\Review\AddReviewArgs;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent;

class AddReviewMutation extends AbstractMutation
{
    private ReviewMutationResponse $response;

    private ReviewMutationResolver $resolver;

    /**
     * @param \App\GraphQL\Response\Review\ReviewMutationResponse $response
     * @param \App\GraphQL\Resolver\Review\ReviewMutationResolver $resolver
     */
    public function __construct(ReviewMutationResponse $response, ReviewMutationResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => '\App\GraphQL\Response\Review\ReviewMutationResponse', 'args' => 'array', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(MutationTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => AddReviewArgs::args(),
            'resolve' => [$this->resolver, 'addReview'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'addReview';
    }
}