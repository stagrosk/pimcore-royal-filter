<?php

namespace App\GraphQL\Helper;

use OpenDxp\Model\DataObject\Collection;
use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Model\DataObject\Data\Link;
use OpenDxp\Model\Element\AbstractElement;

class ButtonHelper
{
    public static function serialize(
        ?string $text,
        ?Link $link,
        ?AbstractElement $relation,
        string $language,
    ): ?array {
        $resolvedRelation = self::resolveRelation($relation, $language);
        $resolvedLink = self::resolveLink($link);

        if ($resolvedRelation === null && $resolvedLink === null && empty($text)) {
            return null;
        }

        // Priority: relation slug > relation handle > link URL
        $url = $resolvedRelation['slug'] ?? $resolvedRelation['handle'] ?? $resolvedLink['url'] ?? null;
        $target = $resolvedLink['target'] ?? null;

        return [
            'text' => $text,
            'link' => $url,
            'target' => $target,
            'relation' => $resolvedRelation,
        ];
    }

    public static function resolveRelation(?AbstractElement $relation, string $language): ?array
    {
        if ($relation === null) {
            return null;
        }

        if ($relation instanceof ContentPage) {
            return [
                'type' => 'ContentPage',
                'id' => $relation->getId(),
                'handle' => $relation->getHandle($language),
                'slug' => $relation->getSlug($language),
            ];
        }

        if ($relation instanceof Collection) {
            return [
                'type' => 'Collection',
                'id' => $relation->getId(),
                'handle' => $relation->getHandle($language),
                'slug' => $relation->getSlug($language),
            ];
        }

        return null;
    }

    public static function resolveLink(?Link $link): ?array
    {
        if ($link === null || empty($link->getPath())) {
            return null;
        }

        return [
            'url' => $link->getPath(),
            'target' => $link->getTarget() ?: '_self',
        ];
    }
}