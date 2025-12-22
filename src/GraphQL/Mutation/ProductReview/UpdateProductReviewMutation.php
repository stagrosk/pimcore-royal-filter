<?php

namespace App\GraphQL\Mutation\ProductReview;

use App\GraphQL\Mutation\AbstractMutation;
use App\GraphQL\Resolver\ProductReview\ProductReviewMutationResolver;
use App\GraphQL\Response\ProductReview\ProductReviewMutationResponse;
use App\GraphQL\Type\Arguments\ProductReview\UpdateProductReviewArgs;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent;

class UpdateProductReviewMutation extends AbstractMutation
{
    private ProductReviewMutationResponse $response;

    private ProductReviewMutationResolver $resolver;

    /**
     * @param \App\GraphQL\Response\ProductReview\ProductReviewMutationResponse $response
     * @param \App\GraphQL\Resolver\ProductReview\ProductReviewMutationResolver $resolver
     */
    public function __construct(ProductReviewMutationResponse $response, ProductReviewMutationResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => "\App\GraphQL\Response\ProductReview\ProductReviewMutationResponse", 'args' => 'array', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(MutationTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => UpdateProductReviewArgs::args(),
            'resolve' => [$this->resolver, 'updateReview'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'updateProductReview';
    }
}