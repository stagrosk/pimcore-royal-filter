<?php

namespace OpendxpHeadlessContentBundle\Model;

use OpendxpHeadlessContentBundle\Exception\ImplementedByOpenDxpException;
use OpendxpHeadlessContentBundle\Model\NavigationLinkItemInterface;
use OpendxpHeadlessContentBundle\Model\OpenDxp\AbstractOpendxpFieldcollection;
use OpenDxp\Model\Element\AbstractElement;

class NavigationLinkItem extends AbstractOpendxpFieldcollection implements NavigationLinkItemInterface
{
    /**
     * @inheritDoc
     */
    public function getId(): ?int
    {
        return sprintf('%d_link_item_%d', $this->getObject()->getId(), $this->getIndex());
    }

    /**
     * @inheritDoc
     */
    public function getRelatedObject(): ?AbstractElement
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setRelatedObject(AbstractElement $object)
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getSubNavigation(): ?AbstractElement
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setSubNavigation(AbstractElement $subNavigation)
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getIsPartner(): ?bool
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setIsPartner(?bool $isPartner)
    {
        throw new ImplementedByOpenDxpException(__CLASS__, __METHOD__);
    }
}
