<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Helper\ContentElementHelper;
use App\GraphQL\Response\AbstractResponse;
use App\GraphQL\Type\Fragment\ContentElementFragment;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use OpenDxp\Bundle\DataHubBundle\GraphQL\ClassTypeDefinitions;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Resolver\QueryType;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Service;
use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Model\DataObject\ContentPage;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ContentPageResponse extends AbstractResponse
{
    /**
     * @param \OpenDxp\Bundle\DataHubBundle\GraphQL\Service $graphQlService
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \App\GraphQL\Helper\ContentElementHelper $contentElementHelper
     *
     * @throws \Exception
     */
    public function __construct(
        Service $graphQlService,
        EventDispatcherInterface $eventDispatcher,
        private readonly ContentElementHelper $contentElementHelper
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
                'elements' => [
                    'type' => Type::listOf(ContentElementFragment::getType()),
                    'resolve' => function ($source) {
                        $contentPage = ContentPage::getById($source['id']);
                        if (!$contentPage instanceof ContentPage) {
                            return [];
                        }
                        $language = $source['language'] ?? $source['defaultLanguage'];
                        return $this->contentElementHelper->getElements($contentPage, $language);
                    },
                ],
                'canonicals' => [
                    'type' => Type::listOf($this->getCanonicalType()),
                    'resolve' => function ($source) {
                        return $source['canonicals'] ?? [];
                    },
                ],
            ],
        ]);
    }

    /**
     * Get canonical link type definition
     *
     * @return \GraphQL\Type\Definition\ObjectType
     */
    private function getCanonicalType(): ObjectType
    {
        return new ObjectType([
            'name' => 'CanonicalLink',
            'fields' => [
                'language' => [
                    'type' => Type::nonNull(Type::string()),
                    'description' => 'Language code (e.g. en, de, sk, cs)',
                ],
                'handle' => [
                    'type' => Type::string(),
                    'description' => 'Handle for this language',
                ],
                'slug' => [
                    'type' => Type::string(),
                    'description' => 'Slug for this language',
                ],
            ],
        ]);
    }
}
