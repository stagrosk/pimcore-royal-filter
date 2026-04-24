<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Exception\ImplementedByPimcoreException;
use PimcoreHeadlessContentBundle\Model\Pimcore\AbstractPimcoreModel;
use OpenDxp\Model\DataObject\Fieldcollection;

class Navigation extends AbstractPimcoreModel implements NavigationInterface
{
    /**
     * @inheritDoc
     */
    public function getIdentifier(): ?string
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier(?string $identifier): static
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getLinks(): ?Fieldcollection
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setLinks(?Fieldcollection $links): static
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getLinkItems(): array
    {
        return $this->getLinks() instanceof Fieldcollection ? $this->getLinks()->getItems() : [];
    }

    /**
     * @inheritDoc
     */
    public function setLinkItems(array $linkItems): void
    {
        $fc = $this->getLinks() ?: new Fieldcollection();
        $fc->setItems($linkItems);

        $this->setLinks($fc);
    }

    /**
     * @inheritDoc
     */
    public function getStores(): ?array
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setStores(?array $stores)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }
}
