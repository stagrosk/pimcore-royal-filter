<?php

namespace App\GraphQL\Response\Layout;

use App\GraphQL\Response\AbstractResponse;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Pimcore\Model\DataObject\BlogCategory;

class BlogCategoryListResponse extends AbstractResponse
{
    private const THUMBNAIL_CATEGORY = 'blog-category';

    public function __construct()
    {
        parent::__construct([
            'name' => 'getBlogCategories',
            'fields' => [
                'items' => [
                    'type' => Type::listOf($this->getBlogCategoryType()),
                    'resolve' => function ($source) {
                        $language = $source['language'];
                        $result = [];

                        foreach ($source['items'] as $item) {
                            $cat = BlogCategory::getById($item['id']);
                            if (!$cat instanceof BlogCategory) {
                                continue;
                            }

                            $image = $cat->getImage();

                            $result[] = [
                                'id' => $cat->getId(),
                                'title' => $cat->getTitle($language),
                                'description' => $cat->getDescription($language),
                                'slug' => $cat->getSlug($language),
                                'handle' => $cat->getHandle($language),
                                'image' => $image ? [
                                    'path' => $image->getFullPath(),
                                    'thumbnail' => $image->getThumbnail(self::THUMBNAIL_CATEGORY)?->getPath(),
                                ] : null,
                                'sortOrder' => $cat->getSortOrder(),
                            ];
                        }

                        return $result;
                    },
                ],
            ],
        ]);
    }

    private function getBlogCategoryType(): ObjectType
    {
        return new ObjectType([
            'name' => 'BlogCategoryItem',
            'fields' => [
                'id' => ['type' => Type::int()],
                'title' => ['type' => Type::string()],
                'description' => ['type' => Type::string()],
                'slug' => ['type' => Type::string()],
                'handle' => ['type' => Type::string()],
                'image' => ['type' => new ObjectType([
                    'name' => 'BlogCategoryImage',
                    'fields' => [
                        'path' => ['type' => Type::string()],
                        'thumbnail' => ['type' => Type::string()],
                    ],
                ])],
                'sortOrder' => ['type' => Type::int()],
            ],
        ]);
    }
}
