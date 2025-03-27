<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Model\Pimcore\PimcoreModelInterface;

interface SlugAwareInterface extends PimcoreModelInterface
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
    public function getAbsolutePath(?string $language = null): ?string;

    /**
     * @param string|null $absolutePath
     * @param string|null $language
     *
     * @return $this
     */
    public function setAbsolutePath(?string $absolutePath, ?string $language = null): static;
}
