<?php

namespace App\GraphQL\Query\Layout;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Layout\BlogCategoryListResolver;
use App\GraphQL\Response\Layout\BlogCategoryListResponse;
use App\GraphQL\Type\Arguments\Layout\BlogCategoryListArgs;
use Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class BlogCategoryListQuery extends AbstractQuery
{
    public function __construct(
        private readonly BlogCategoryListResponse $response,
        private readonly BlogCategoryListResolver $resolver
    ) {
    }

    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => BlogCategoryListArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    public function getOperationName(): string
    {
        return 'getBlogCategories';
    }
}
