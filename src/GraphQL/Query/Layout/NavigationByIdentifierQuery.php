<?php

namespace App\GraphQL\Query\Layout;

use App\GraphQL\Query\AbstractQuery;
use App\GraphQL\Resolver\Layout\NavigationByIdentifierResolver as NavigationByIdentifierResolver;
use App\GraphQL\Response\Layout\NavigationResponse;
use App\GraphQL\Type\Arguments\Layout\NavigationByIdentifierArgs as NavigationByIdentifierArguments;
use JetBrains\PhpStorm\ArrayShape;
use OpenDxp\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent;

class NavigationByIdentifierQuery extends AbstractQuery
{
    private NavigationResponse $response;

    private NavigationByIdentifierResolver $resolver;

    /**
     * @param \App\GraphQL\Response\Layout\NavigationResponse $response
     * @param \App\GraphQL\Resolver\Layout\NavigationByIdentifierResolver $resolver
     */
    public function __construct(NavigationResponse $response, NavigationByIdentifierResolver $resolver)
    {
        $this->response = $response;
        $this->resolver = $resolver;
    }

    #[ArrayShape(['type' => "\App\GraphQL\Type\Response\Layout\NavigationResponse", 'args' => 'array|array[]', 'resolve' => 'array'])]
    /**
     * @param \Pimcore\Bundle\DataHubBundle\Event\GraphQL\Model\QueryTypeEvent $event
     *
     * @return array
     */
    public function onPreBuild(QueryTypeEvent $event): array
    {
        return [
            'type' => $this->response,
            'args' => NavigationByIdentifierArguments::args(),
            'resolve' => [$this->resolver, 'resolve'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function getOperationName(): string
    {
        return 'navigationByIdentifier';
    }
}
