<?php

namespace App\GraphQL\Query\Wishlist;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Wishlist\WishlistResolver;
use App\GraphQL\Response\Wishlist\WishlistResponse;
use App\GraphQL\Type\Arguments\Wishlist\WishlistArgs;
use JetBrains\PhpStorm\ArrayShape;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class WishlistQuery extends AbstractQuery
{
    private WishlistResponse $response;

    private WishlistResolver $resolver;

    /**
     * @param \App\GraphQL\Response\Wishlist\WishlistResponse $response
     * @param \App\GraphQL\Resolver\Wishlist\WishlistResolver $resolver
     */
    public function __construct(WishlistResponse $response, WishlistResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => "\App\GraphQL\Response\Wishlist\WishlistResponse", 'args' => 'array', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => WishlistArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'getWishlist';
    }
}