<?php

namespace App\Pimcore\Model\DataObject;

use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class Product extends \Pimcore\Model\DataObject\Product implements SlugAwareInterface
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
