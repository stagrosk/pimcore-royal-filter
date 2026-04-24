<?php

namespace App\GraphQL\Resolver\Layout;

use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use OpenDxp\Model\DataObject\BlogCategory;
use OpenDxp\Model\DataObject\BlogPost;
use OpenDxp\Tool;

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
            $categoryList = BlogCategory::getList();
            $categoryList->setLocale($language);
            $categoryList->addConditionParam('slug = ?', $categorySlug);
            $categoryList->setLimit(1);
            $categoryResults = $categoryList->getObjects();

            if (empty($categoryResults)) {
                return ['items' => [], 'totalCount' => 0, 'language' => $language];
            }

            $cat = reset($categoryResults);
            $list->addConditionParam('blogCategories LIKE ?', '%,' . $cat->getId() . ',%');
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
