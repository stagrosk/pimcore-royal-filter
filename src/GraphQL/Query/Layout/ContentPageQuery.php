<?php

namespace App\GraphQL\Query\Layout;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Layout\ContentPageResolver;
use App\GraphQL\Response\Layout\ContentPageResponse;
use App\GraphQL\Type\Arguments\Layout\ContentPageArgs;
use JetBrains\PhpStorm\ArrayShape;
use OpenDxp\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class ContentPageQuery extends AbstractQuery
{
    private ContentPageResponse $response;

    private ContentPageResolver $resolver;

    /**
     * @param \App\GraphQL\Response\Layout\ContentPageResponse $response
     * @param \App\GraphQL\Resolver\Layout\ContentPageResolver $resolver
     */
    public function __construct(
        ContentPageResponse $response,
        ContentPageResolver $resolver
    ) {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => "\App\GraphQL\Type\Response\Layout\ContentPageBySlugResponse", 'args' => 'array|array[]', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @throws \Exception
     *
     * @return array
     */
    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => ContentPageArgs::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    /**
     * @return string
     */
    public function getOperationName(): string
    {
        return 'getContentPageBySlugOrHandle';
    }
}
