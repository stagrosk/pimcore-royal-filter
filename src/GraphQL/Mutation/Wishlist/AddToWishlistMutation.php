<?php

namespace App\GraphQL\Mutation\Wishlist;

use App\GraphQL\Mutation\AbstractMutation;
use App\GraphQL\Resolver\Wishlist\WishlistMutationResolver;
use App\GraphQL\Response\Wishlist\WishlistMutationResponse;
use App\GraphQL\Type\Arguments\Wishlist\WishlistMutationArgs;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent;

class AddToWishlistMutation extends AbstractMutation
{
    private WishlistMutationResponse $response;

    private WishlistMutationResolver $resolver;

    /**
     * @param \App\GraphQL\Response\Wishlist\WishlistMutationResponse $response
     * @param \App\GraphQL\Resolver\Wishlist\WishlistMutationResolver $resolver
     */
    public function __construct(WishlistMutationResponse $response, WishlistMutationResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => "\App\GraphQL\Response\Wishlist\WishlistMutationResponse", 'args' => 'array', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\MutationTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(MutationTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => WishlistMutationArgs::args(),
            'resolve' => [$this->resolver, 'addToWishlist'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'addToWishlist';
    }
}