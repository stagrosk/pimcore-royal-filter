<?php

declare(strict_types=1);

namespace App\Pimcore\Model\DataObject;

use PimcoreHeadlessContentBundle\Model\SlugAwareInterface;

class BlogPost extends \Pimcore\Model\DataObject\BlogPost implements SlugAwareInterface
{
    public function getSlugValue(?string $language = null): ?string
    {
        return $this->getTitle($language);
    }
}
