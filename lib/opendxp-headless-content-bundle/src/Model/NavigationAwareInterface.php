<?php

namespace OpendxpHeadlessContentBundle\Model;

use OpendxpHeadlessContentBundle\Model\OpenDxp\OpendxpModelInterface;

interface NavigationAwareInterface extends OpendxpModelInterface
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
