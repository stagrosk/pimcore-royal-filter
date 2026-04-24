<?php

namespace OpendxpHeadlessContentBundle\Model;

interface ContentPageInterface extends SlugAwareInterface, NavigationAwareInterface
{
    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getName(?string $language = null): ?string;

    /**
     * @param string|null $name
     * @param string|null $language
     *
     * @return $this
     */
    public function setName(?string $name, ?string $language = null): static;
}
