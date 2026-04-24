<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Model\OpenDxp\PimcoreModelInterface;

interface NavigationAwareInterface extends PimcoreModelInterface
{
    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getNavigationTitle(?string $language = null): ?string;

    /**
     * @param string|null $language
     *
     * @return array
     */
    public function getNavigationAdditionalData(?string $language = null): array;
}
