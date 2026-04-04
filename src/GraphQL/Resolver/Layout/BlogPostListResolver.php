<?php

namespace App\GraphQL\Resolver\Layout;

use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Model\DataObject\BlogCategory;
use Pimcore\Model\DataObject\BlogPost;
use Pimcore\Tool;

class BlogPostListResolver extends AbstractResolver
{
    public function resolve($source, $args, $context, ResolveInfo $info): array
    {
        $language = $args['language'] ?: Tool::getDefaultLanguage();
        $categorySlug = $args['categorySlug'] ?? null;
        $limit = $args['limit'] ?? 20;
        $offset = $args['offset'] ?? 0;

        $list = BlogPost::getList();
        $list->setLocale($language);
        $list->addConditionParam('status = ?', 'published');
        $list->setOrderKey('publishDate');
        $list->setOrder('desc');
        $list->setLimit($limit);
        $list->setOffset($offset);

        if (!empty($categorySlug)) {
            $category = BlogCategory::getList();
            $category->setLocale($language);
            $category->addConditionParam('slug = ?', $categorySlug);
            $category->setLimit(1);
            $categories = $category->getObjects();

            if (!empty($categories)) {
                $cat = reset($categories);
                $list->addConditionParam('blogCategories LIKE ?', '%,' . $cat->getId() . ',%');
            }
        }

        $posts = $list->getObjects();
        $totalCount = $list->getTotalCount();

        $items = [];
        foreach ($posts as $post) {
            $items[] = [
                'id' => $post->getId(),
                'language' => $language,
            ];
        }

        return [
            'items' => $items,
            'totalCount' => $totalCount,
            'language' => $language,
        ];
    }
}
