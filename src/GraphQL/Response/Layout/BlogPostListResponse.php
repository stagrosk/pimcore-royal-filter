<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use OpenDxp\Model\DataObject\BlogPost;

class BlogPostListResponse extends AbstractResponse
{
    private const THUMBNAIL_CARD = 'blog-card';

    public function __construct()
    {
        parent::__construct([
            'name' => 'getBlogPosts',
            'fields' => [
                'items' => [
                    'type' => Type::listOf($this->getBlogPostCardType()),
                    'resolve' => function ($source) {
                        $language = $source['language'];
                        $result = [];

                        foreach ($source['items'] as $item) {
                            $post = BlogPost::getById($item['id']);
                            if (!$post instanceof BlogPost) {
                                continue;
                            }

                            $featuredImage = $post->getFeaturedImage();
                            $categories = [];
                            foreach ($post->getBlogCategories() as $cat) {
                                $categories[] = [
                                    'id' => $cat->getId(),
                                    'title' => $cat->getTitle($language),
                                    'slug' => $cat->getSlug($language),
                                ];
                            }

                            $result[] = [
                                'id' => $post->getId(),
                                'title' => $post->getTitle($language),
                                'slug' => $post->getSlug($language),
                                'excerpt' => $post->getExcerpt($language),
                                'author' => $post->getAuthor(),
                                'publishDate' => $post->getPublishDate()?->format('c'),
                                'isFeatured' => $post->getIsFeatured(),
                                'featuredImage' => $featuredImage ? [
                                    'path' => $featuredImage->getFullPath(),
                                    'card' => $featuredImage->getThumbnail(self::THUMBNAIL_CARD)?->getPath(),
                                ] : null,
                                'categories' => $categories,
                            ];
                        }

                        return $result;
                    },
                ],
                'totalCount' => [
                    'type' => Type::int(),
                    'resolve' => fn ($source) => $source['totalCount'],
                ],
            ],
        ]);
    }

    private function getBlogPostCardType(): ObjectType
    {
        return new ObjectType([
            'name' => 'BlogPostCard',
            'fields' => [
                'id' => ['type' => Type::int()],
                'title' => ['type' => Type::string()],
                'slug' => ['type' => Type::string()],
                'excerpt' => ['type' => Type::string()],
                'author' => ['type' => Type::string()],
                'publishDate' => ['type' => Type::string()],
                'isFeatured' => ['type' => Type::boolean()],
                'featuredImage' => ['type' => new ObjectType([
                    'name' => 'BlogPostCardImage',
                    'fields' => [
                        'path' => ['type' => Type::string()],
                        'card' => ['type' => Type::string()],
                    ],
                ])],
                'categories' => ['type' => Type::listOf(new ObjectType([
                    'name' => 'BlogPostCardCategory',
                    'fields' => [
                        'id' => ['type' => Type::int()],
                        'title' => ['type' => Type::string()],
                        'slug' => ['type' => Type::string()],
                    ],
                ]))],
            ],
        ]);
    }
}
