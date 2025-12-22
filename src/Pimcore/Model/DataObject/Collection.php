<?php

namespace App\Pimcore\Model\DataObject;

use PimcoreHeadlessContentBundle\Model\NavigationAwareInterface;
use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class Collection extends \Pimcore\Model\DataObject\Collection implements SlugAwareInterface, NavigationAwareInterface
{
    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getSlugValue(?string $language = null): ?string
    {
        return $this->getTitle($language);
    }

    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getNavigationTitle(?string $language = null): ?string
    {
        return $this->getTitle($language);
    }

    /**
     * @param string|null $language
     *
     * @return array
     */
    public function getNavigationAdditionalData(?string $language = null): array
    {
        return [];
    }
}
