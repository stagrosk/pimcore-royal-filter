<?php

namespace App\GraphQL\Resolver\Layout;

use App\Exception\TranslatableException;
use App\GraphQL\Helper\NavigationHelper;
use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use Pimcore\Bundle\DataHubBundle\GraphQL\Service as GraphQLService;
use Pimcore\Cache;
use Pimcore\Model\DataObject\ContentPage;
use Pimcore\Model\DataObject\NavigationItem;
use Pimcore\Model\DataObject\NavigationItem\Listing;
use Pimcore\Tool;
use PimcoreHeadlessContentBundle\Model\NavigationAwareInterface;
use PimcoreHeadlessContentBundle\Model\NavigationInterface;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class NavigationByIdentifierResolver extends AbstractResolver
{
    private GraphQLService $service;

    private array $allowedPimcoreClassNames;

    /**
     * @param \Pimcore\Bundle\DataHubBundle\GraphQL\Service $service
     * @param array $allowedPimcoreClassNames
     */
    public function __construct(
        GraphQLService $service,
        array $allowedPimcoreClassNames = []
    ) {
        $this->service = $service;
        $this->allowedPimcoreClassNames = $allowedPimcoreClassNames;
    }

    /**
     * @inheritDoc
     */
    public function resolve($source, $args, $context, ResolveInfo $info)
    {
        $identifier = $args['identifier'];
        $language = $args['language'] ?: Tool::getDefaultLanguage();

        $this->service->getLocaleService()->setLocale($language);

        $list = new Listing();
        $list->filterByIdentifier($identifier);
        $list->setLimit(1);
        $data = $list->getData();
        $navigation = reset($data);

        if (!$navigation instanceof NavigationItem) {
            throw new TranslatableException(
                sprintf('navigation with identifier [%s] not found', $identifier),
                'cc-error-navigation-not-found',
                'CC_NAVIGATION_NOT_FOUND'
            );
        }

        $cacheKey = NavigationHelper::getCacheKey($navigation, $language);
        $dataFromCache = Cache::load($cacheKey);
        if ($dataFromCache) {
            return $dataFromCache;
        }

        $data = [
            'identifier' => $navigation->getIdentifier(),
            'linkItems' => $this->getItemsForNavigation($navigation, $language),
        ];

        Cache::save($data, $cacheKey);

        return $data;
    }

    /**
     * @param \PimcoreHeadlessContentBundle\Model\NavigationInterface $navigation
     * @param string $language
     *
     * @return array
     */
    private function getItemsForNavigation(NavigationInterface $navigation, string $language): array
    {
        $items = [];

        foreach ($navigation->getLinkItems() as $linkItem) {
            /** @var \Pimcore\Model\DataObject\Concrete $object */
            $object = $linkItem->getRelatedObject();

            if (!$object instanceof NavigationAwareInterface) {
                continue;
            }

            if (!in_array($object->getClassName(), $this->allowedPimcoreClassNames, true)) {
                continue;
            }

            $item = [
                'title' => $object->getNavigationTitle($language),
                'additionalData' => json_encode($object->getNavigationAdditionalData($language)),
                'className' => $object->getClassName()
            ];

            if ($object instanceof ContentPage) {
                $item['nameInNavigation'] = $object->getNameInNavigation($language);
            }

            if ($object instanceof SlugAwareInterface) {
                $item = array_merge($item, [
                    'handle' => $object->getHandle($language),
                    'slug' => $object->getSlug($language),
                ]);
            }

            $subNavigation = $linkItem->getSubNavigation();

            if ($subNavigation instanceof NavigationInterface) {
                $item = array_merge($item, [
                    'linkItems' => $this->getItemsForNavigation($subNavigation, $language),
                ]);
            }

            $items[] = $item;
        }

        return $items;
    }
}
