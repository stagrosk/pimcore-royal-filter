<?php

namespace App\GraphQL\Query\Layout;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Layout\BlogPostResolver;
use App\GraphQL\Response\Layout\BlogPostResponse;
use App\GraphQL\Type\Arguments\Layout\BlogPostArgs;
use OpenDxp\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class BlogPostQuery extends AbstractQuery
{
    public function __construct(
        private readonly BlogPostResponse $response,
        private readonly BlogPostResolver $resolver
    ) {
    }

    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => BlogPostArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    public function getOperationName(): string
    {
        return 'getBlogPostBySlugOrHandle';
    }
}
