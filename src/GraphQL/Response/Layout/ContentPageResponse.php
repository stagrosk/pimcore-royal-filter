<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Bundle\DataHubBundle\GraphQL\ClassTypeDefinitions;
use Pimcore\Bundle\DataHubBundle\GraphQL\Resolver\QueryType;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Pimcore\Model\DataObject\ClassDefinition;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ContentPageResponse extends AbstractResponse
{
    /**
     * @param \Pimcore\Bundle\DataHubBundle\GraphQL\Service $graphQlService
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @throws \Exception
     */
    public function __construct(
        Service $graphQlService,
        EventDispatcherInterface $eventDispatcher
    ) {
        $class = ClassDefinition::getByName('ContentPage');
        $resolver = new QueryType($eventDispatcher, $class, null);
        $resolver->setGraphQlService($graphQlService);

        // just for debug:event-dispatcher
        if (empty(ClassTypeDefinitions::$definitions)) {
            ClassTypeDefinitions::build($graphQlService, ['clientname' => null]);
        }

        parent::__construct([
            'name' => 'getContentPageBySlugOrHandle',
            'fields' => [
                'content' => [
                    'type' => ClassTypeDefinitions::get($class),
                    'resolve' => function ($source, $args, $context, ResolveInfo $info) use ($resolver) {
                        return $resolver->resolveObjectGetter(null, $source, $context, $info);
                    },
                ],
            ],
        ]);
    }
}
