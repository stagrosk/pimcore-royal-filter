<?php

declare(strict_types=1);

namespace App\GraphQL\DataHub;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

/**
 * Replacement for OpenDxp\Bundle\DataHubBundle\GraphQL\DataObjectType\LinkType that wires the
 * custom App\GraphQL\DataHub\LinkResolver into the `path` field. All other fields delegate to
 * the same resolver (which inherits the default behavior from the base class).
 */
class LinkType extends ObjectType
{
    private static ?self $instance = null;

    public static function getInstance(LinkResolver $resolver): self
    {
        if (self::$instance === null) {
            self::$instance = new self([
                'name' => 'Link',
                'fields' => [
                    'text' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveText']],
                    'path' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolvePath']],
                    'target' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveTarget']],
                    'anchor' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveAnchor']],
                    'title' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveTitle']],
                    'accesskey' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveAccesskey']],
                    'rel' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveRel']],
                    'class' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveClass']],
                    'attributes' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveAttributes']],
                    'tabindex' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveTabindex']],
                    'parameters' => ['type' => Type::string(), 'resolve' => [$resolver, 'resolveParameters']],
                ],
            ]);
        }

        return self::$instance;
    }
}
