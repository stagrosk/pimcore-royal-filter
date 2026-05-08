<?php

declare(strict_types=1);

namespace App\GraphQL\DataHub;

use OpenDxp\Bundle\DataHubBundle\GraphQL\DataObjectQueryFieldConfigGenerator\Link as BaseLink;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Service as GraphQlService;
use OpenDxp\Model\DataObject\ClassDefinition\Data;

/**
 * Decorates the data-hub field config generator for DataObject Link data type so the GraphQL
 * Link field uses our custom LinkType (with handle-aware path resolver).
 */
class LinkFieldConfigGenerator extends BaseLink
{
    public function __construct(GraphQlService $graphQlService, private readonly LinkResolver $linkResolver)
    {
        parent::__construct($graphQlService);
    }

    public function getFieldType(Data $fieldDefinition, $class = null, $container = null)
    {
        return LinkType::getInstance($this->linkResolver);
    }
}
