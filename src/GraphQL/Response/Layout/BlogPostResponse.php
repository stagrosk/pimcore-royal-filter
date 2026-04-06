<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Helper\ContentElementHelper;
use App\GraphQL\Response\AbstractResponse;
use App\GraphQL\Type\Fragment\ContentElementFragment;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Pimcore\Bundle\DataHubBundle\GraphQL\ClassTypeDefinitions;
use Pimcore\Bundle\DataHubBundle\GraphQL\Resolver\QueryType;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service;
use Pimcore\Model\DataObject\BlogPost;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Tool;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BlogPostResponse extends AbstractResponse
{
    private const THUMBNAIL_CARD = self::THUMBNAIL_CARD;
    private const THUMBNAIL_DETAIL = self::THUMBNAIL_DETAIL;

    public function __construct(
        Service $graphQlService,
        EventDispatcherInterface $eventDispatcher,
        private readonly ContentElementHelper $contentElementHelper
    ) {
        $class = ClassDefinition::getByName('BlogPost');
        $resolver = new QueryType($eventDispatcher, $class, null);
        $resolver->setGraphQlService($graphQlService);

        if (empty(ClassTypeDefinitions::$definitions)) {
            ClassTypeDefinitions::build($graphQlService, ['clientname' => null]);
        }

        parent::__construct([
            'name' => 'getBlogPostBySlugOrHandle',
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
                        $blogPost = BlogPost::getById($source['id']);
                        if (!$blogPost instanceof BlogPost) {
                            return [];
                        }
                        $language = $source['language'] ?? $source['defaultLanguage'];
                        return $this->contentElementHelper->getElements($blogPost, $language);
                    },
                ],
                'meta' => [
                    'type' => $this->getBlogMetaType(),
                    'resolve' => function ($source) {
                        $blogPost = BlogPost::getById($source['id']);
                        if (!$blogPost instanceof BlogPost) {
                            return null;
                        }
                        $language = $source['language'] ?? $source['defaultLanguage'];
                        return $this->resolveMeta($blogPost, $language);
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

    private function resolveMeta(BlogPost $post, string $language): array
    {
        $featuredImage = $post->getFeaturedImage();
        $categories = [];
        foreach ($post->getBlogCategories() as $cat) {
            $categories[] = [
                'id' => $cat->getId(),
                'title' => $cat->getTitle($language),
                'slug' => $cat->getSlug($language),
            ];
        }

        $relatedPosts = [];
        foreach ($post->getRelatedPosts() as $related) {
            if ($related instanceof BlogPost && $related->getStatus() === 'published') {
                $relatedImage = $related->getFeaturedImage();
                $relatedPosts[] = [
                    'id' => $related->getId(),
                    'title' => $related->getTitle($language),
                    'slug' => $related->getSlug($language),
                    'excerpt' => $related->getExcerpt($language),
                    'featuredImage' => $relatedImage ? [
                        'path' => $relatedImage->getFullPath(),
                        'card' => $relatedImage->getThumbnail(self::THUMBNAIL_CARD)?->getPath(),
                    ] : null,
                    'publishDate' => $related->getPublishDate()?->format('c'),
                ];
            }
        }

        return [
            'title' => $post->getTitle($language),
            'excerpt' => $post->getExcerpt($language),
            'seoTitle' => $post->getSeoTitle($language),
            'seoDescription' => $post->getSeoDescription($language),
            'author' => $post->getAuthor(),
            'publishDate' => $post->getPublishDate()?->format('c'),
            'isFeatured' => $post->getIsFeatured(),
            'featuredImage' => $featuredImage ? [
                'path' => $featuredImage->getFullPath(),
                'detail' => $featuredImage->getThumbnail(self::THUMBNAIL_DETAIL)?->getPath(),
                'card' => $featuredImage->getThumbnail(self::THUMBNAIL_CARD)?->getPath(),
            ] : null,
            'categories' => $categories,
            'relatedPosts' => $relatedPosts,
        ];
    }

    private function getBlogMetaType(): ObjectType
    {
        return new ObjectType([
            'name' => 'BlogPostMeta',
            'fields' => [
                'title' => ['type' => Type::string()],
                'excerpt' => ['type' => Type::string()],
                'seoTitle' => ['type' => Type::string()],
                'seoDescription' => ['type' => Type::string()],
                'author' => ['type' => Type::string()],
                'publishDate' => ['type' => Type::string()],
                'isFeatured' => ['type' => Type::boolean()],
                'featuredImage' => ['type' => $this->getImageType('BlogPostFeaturedImage', true)],
                'categories' => ['type' => Type::listOf(new ObjectType([
                    'name' => 'BlogPostCategory',
                    'fields' => [
                        'id' => ['type' => Type::int()],
                        'title' => ['type' => Type::string()],
                        'slug' => ['type' => Type::string()],
                    ],
                ]))],
                'relatedPosts' => ['type' => Type::listOf(new ObjectType([
                    'name' => 'BlogRelatedPost',
                    'fields' => [
                        'id' => ['type' => Type::int()],
                        'title' => ['type' => Type::string()],
                        'slug' => ['type' => Type::string()],
                        'excerpt' => ['type' => Type::string()],
                        'featuredImage' => ['type' => $this->getImageType('BlogRelatedPostImage', false)],
                        'publishDate' => ['type' => Type::string()],
                    ],
                ]))],
            ],
        ]);
    }

    private function getImageType(string $name, bool $includeDetail): ObjectType
    {
        $fields = [
            'path' => ['type' => Type::string()],
            'card' => ['type' => Type::string()],
        ];

        if ($includeDetail) {
            $fields['detail'] = ['type' => Type::string()];
        }

        return new ObjectType([
            'name' => $name,
            'fields' => $fields,
        ]);
    }

    private function getCanonicalType(): ObjectType
    {
        return new ObjectType([
            'name' => 'BlogCanonicalLink',
            'fields' => [
                'language' => ['type' => Type::nonNull(Type::string())],
                'handle' => ['type' => Type::string()],
                'slug' => ['type' => Type::string()],
            ],
        ]);
    }
}
