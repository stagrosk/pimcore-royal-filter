<?php

namespace App\GraphQL\Resolver\Layout;

use App\Exception\TranslatableException;
use App\GraphQL\Helper\NavigationHelper;
use App\GraphQL\Resolver\AbstractResolver;
use GraphQL\Type\Definition\ResolveInfo;
use OpenDxp\Bundle\DataHubBundle\GraphQL\Service as GraphQLService;
use OpenDxp\Cache;
use OpenDxp\Model\DataObject\ContentPage;
use OpenDxp\Model\DataObject\NavigationItem;
use OpenDxp\Model\DataObject\NavigationItem\Listing;
use OpenDxp\Tool;
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
//            return $dataFromCache;
        }

        $data = [
            'identifier' => $navigation->getIdentifier(),
            'isPartner' => $navigation->getIsPartner() ?? false,
            'linkItems' => $this->getItemsForNavigation($navigation, $language),
        ];

        Cache::save($data, $cacheKey);

        return $data;
    }

    /**
     * @param \PimcoreHeadlessContentBundle\Model\NavigationInterface $navigation
     * @param string $language
     * @param int|null $parentObjectId
     *
     * @return array
     */
    private function getItemsForNavigation(NavigationInterface $navigation, string $language, ?int $parentObjectId = null): array
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

            // Skip if this object is the same as parent (avoid duplicates)
            if ($parentObjectId !== null && $object->getId() === $parentObjectId) {
                continue;
            }

            $item = [
                'title' => $object->getNavigationTitle($language),
                'additionalData' => json_encode($object->getNavigationAdditionalData($language)),
                'className' => $object->getClassName(),
                'handle' => null,
                'slug' => null,
                'canonicals' => [],
                'apiId' => method_exists($object, 'getApiId') ? $object->getApiId() : null,
                'pimcoreId' => $object->getId(),
                'isPartner' => $linkItem->getIsPartner() ?? false,
                'imageTile' => $this->getImageUrl($object, 'category-tile'),
                'imagePreview' => $this->getImageUrl($object, 'category-preview'),
                'description' => method_exists($object, 'getDescription') ? $object->getDescription($language) : null,
            ];

            if ($object instanceof ContentPage) {
                $item['nameInNavigation'] = $object->getNameInNavigation($language);
            }

            if ($object instanceof SlugAwareInterface) {
                $item['handle'] = $object->getHandle($language);
                $item['slug'] = $object->getSlug($language);
                $item['canonicals'] = $this->getCanonicals($object);
            }

            $subNavigation = $linkItem->getSubNavigation();
            if ($subNavigation instanceof NavigationInterface) {
                $item = array_merge($item, [
                    'linkItems' => $this->getItemsForNavigation($subNavigation, $language, $object->getId()),
                ]);
            }

            $items[] = $item;
        }

        return $items;
    }

    /**
     * Get image URL from object if available (using thumbnail)
     *
     * @param object $object
     * @param string $thumbnailName
     *
     * @return string|null
     */
    private function getImageUrl(object $object, string $thumbnailName): ?string
    {
        if (!method_exists($object, 'getImage')) {
            return null;
        }

        $image = $object->getImage();

        if ($image === null) {
            return null;
        }

        return $image->getThumbnail($thumbnailName)->getPath();
    }

    /**
     * Get canonicals with handles for all language mutations
     *
     * @param \PimcoreHeadlessContentBundle\Model\SlugAwareInterface $object
     *
     * @return array
     */
    private function getCanonicals(SlugAwareInterface $object): array
    {
        $canonicals = [];
        $validLanguages = Tool::getValidLanguages();

        foreach ($validLanguages as $lang) {
            $handle = $object->getHandle($lang);
            $slug = $object->getSlug($lang);

            if (!empty($handle) || !empty($slug)) {
                $canonicals[] = [
                    'language' => $lang,
                    'handle' => $handle,
                    'slug' => $slug,
                ];
            }
        }

        return $canonicals;
    }
}
