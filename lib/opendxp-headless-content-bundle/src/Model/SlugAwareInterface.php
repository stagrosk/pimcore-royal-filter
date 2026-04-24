<?php

namespace OpendxpHeadlessContentBundle\Model;

use OpendxpHeadlessContentBundle\Model\OpenDxp\OpendxpModelInterface;

interface SlugAwareInterface extends OpendxpModelInterface
{
    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getSlugValue(?string $language = null): ?string;

    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getSlug(?string $language = null): ?string;

    /**
     * @param string|null $slug
     * @param string|null $language
     *
     * @return $this
     */
    public function setSlug(?string $slug, ?string $language = null): static;

    /**
     * @param string|null $language
     *
     * @return string|null
     */
    public function getHandle(?string $language = null): ?string;

    /**
     * @param string|null $handle
     * @param string|null $language
     *
     * @return $this
     */
    public function setHandle(?string $handle, ?string $language = null): static;
}
