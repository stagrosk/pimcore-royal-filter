<?php

namespace App\GraphQL\Helper;

use PimcoreHeadlessContentBundle\Model\NavigationInterface;

class NavigationHelper
{
    /**
     * @param \PimcoreHeadlessContentBundle\Model\NavigationInterface $navigation
     * @param string $language
     *
     * @return string
     */
    public static function getCacheKey(NavigationInterface $navigation, string $language): string
    {
        return sprintf('navigation_%s_%s', md5($navigation->getId()), $language);
    }
}
