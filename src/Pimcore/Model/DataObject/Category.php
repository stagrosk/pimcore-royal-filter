<?php

namespace App\Pimcore\Model\DataObject;

use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class Category extends \Pimcore\Model\DataObject\Category implements SlugAwareInterface
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
}
