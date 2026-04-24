<?php

namespace App\GraphQL\Resolver\Layout;

use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use OpenDxp\Model\DataObject\BlogCategory;
use OpenDxp\Tool;

class BlogCategoryListResolver extends AbstractResolver
{
    public function resolve($source, $args, $context, ResolveInfo $info): array
    {
        $language = $args['language'] ?: Tool::getDefaultLanguage();

        $list = BlogCategory::getList();
        $list->setLocale($language);
        $list->setUnpublished(false);
        $list->setOrderKey('sortOrder');
        $list->setOrder('asc');

        $categories = $list->getObjects();

        $items = [];
        foreach ($categories as $cat) {
            $items[] = [
                'id' => $cat->getId(),
                'language' => $language,
            ];
        }

        return [
            'items' => $items,
            'language' => $language,
        ];
    }
}
