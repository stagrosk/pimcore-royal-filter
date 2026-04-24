<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Exception\ImplementedByPimcoreException;
use PimcoreHeadlessContentBundle\Model\NavigationLinkItemInterface;
use PimcoreHeadlessContentBundle\Model\Pimcore\AbstractPimcoreFieldcollection;
use OpenDxp\Model\Element\AbstractElement;

class NavigationLinkItem extends AbstractPimcoreFieldcollection implements NavigationLinkItemInterface
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
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setRelatedObject(AbstractElement $object)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getSubNavigation(): ?AbstractElement
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setSubNavigation(AbstractElement $subNavigation)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function getIsPartner(): ?bool
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function setIsPartner(?bool $isPartner)
    {
        throw new ImplementedByPimcoreException(__CLASS__, __METHOD__);
    }
}
