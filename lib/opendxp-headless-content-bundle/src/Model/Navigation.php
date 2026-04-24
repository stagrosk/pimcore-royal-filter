<?php

namespace OpendxpHeadlessContentBundle\Model;

use OpendxpHeadlessContentBundle\Exception\ImplementedByOpenDxpException;
use OpendxpHeadlessContentBundle\Model\OpenDxp\AbstractOpendxpModel;
use OpenDxp\Model\DataObject\Fieldcollection;

class Navigation extends AbstractOpendxpModel implements NavigationInterface
{
    /**
     * @inheritDoc
     */
    public function getIdentifier(): ?string
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier(?string $identifier): static
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getLinks(): ?Fieldcollection
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setLinks(?Fieldcollection $links): static
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
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
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setStores(?array $stores)
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }
}
