<?php

declare(strict_types=1);

namespace App\OpenDxp\Model\DataObject;

use OpendxpHeadlessContentBundle\Model\SlugAwareInterface;

class BlogPost extends \OpenDxp\Model\DataObject\BlogPost implements SlugAwareInterface
{
    public function getSlugValue(?string $language = null): ?string
    {
        return $this->getTitle($language);
    }
}
