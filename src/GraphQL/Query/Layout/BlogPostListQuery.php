<?php

namespace App\GraphQL\Query\Layout;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Layout\BlogPostListResolver;
use App\GraphQL\Response\Layout\BlogPostListResponse;
use App\GraphQL\Type\Arguments\Layout\BlogPostListArgs;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class BlogPostListQuery extends AbstractQuery
{
    public function __construct(
        private readonly BlogPostListResponse $response,
        private readonly BlogPostListResolver $resolver
    ) {
    }

    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => BlogPostListArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    public function getOperationName(): string
    {
        return 'getBlogPosts';
    }
}
