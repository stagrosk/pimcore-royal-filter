<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Exception\ImplementedByPimcoreException;
use PimcoreHeadlessContentBundle\Model\Pimcore\AbstractPimcoreModel;

class ContentPage extends AbstractPimcoreModel implements ContentPageInterface
{
    /**
     * @inheritDoc
     */
    public function getSlugValue(?string $language = null): ?string
    {
        return $this->getName($language);
    }

    /**
     * @inheritDoc
     */
    public function getSlug(?string $language = null): ?String
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setSlug(?string $slug, ?string $language = null): static
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getHandle(?string $language = null): ?string
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setHandle(?string $handle, ?string $language = null): static
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getName(?string $language = null): ?string
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setName(?string $name, ?string $language = null): static
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getNavigationTitle(?string $language = null): ?string
    {
        return $this->getName($language);
    }

    /**
     * @inheritDoc
     */
    public function getNavigationAdditionalData(?string $language = null): array
    {
        return [];
    }
}
