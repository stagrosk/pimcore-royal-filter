<?php

declare(strict_types=1);

namespace App\GraphQL\DataHub;

use GraphQL\Type\Definition\ResolveInfo;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Resolver\Link as BaseLinkResolver;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Service as GraphQlService;
use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\DataObject\Data\Link as LinkValue;

/**
 * Override of the data-hub Link resolver so the GraphQL `path` field returns the localized
 * handle of internal Pimcore object targets (e.g. ContentPage), instead of the raw Pimcore
 * tree path. Falls back to fullpath for assets/documents/objects without a localized handle.
 *
 * Language is taken from the LocaleService, which is set by the top-level query argument
 * `defaultLanguage` (e.g. `getContentPage(id: ..., defaultLanguage: "sk")`).
 */
class LinkResolver extends BaseLinkResolver
{
    public function __construct(GraphQlService $graphQlService)
    {
        $this->setGraphQLService($graphQlService);
    }

    public function resolvePath($value = null, $args = [], $context = [], ?ResolveInfo $resolveInfo = null)
    {
        if (!$value instanceof LinkValue) {
            return null;
        }

        if ($value->getLinktype() !== 'internal') {
            return $value->getDirect() ?: null;
        }

        $element = $value->getElement();
        if ($element === null) {
            return null;
        }

        if ($element instanceof Concrete && method_exists($element, 'getHandle')) {
            $language = $this->getGraphQLService()->getLocaleService()->getLocale();
            $handle = $element->getHandle($language);
            if (is_string($handle) && $handle !== '') {
                return $handle;
            }
        }

        return $element->getFullPath();
    }
}
