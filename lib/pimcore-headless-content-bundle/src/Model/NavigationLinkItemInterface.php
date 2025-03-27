<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Model\Pimcore\ResourceInterface;
use Pimcore\Model\Element\AbstractElement;

interface NavigationLinkItemInterface extends ResourceInterface
{
    /**
     * @return \Pimcore\Model\Element\AbstractElement|null
     */
    public function getRelatedObject(): ?AbstractElement;

    /**
     * @param \Pimcore\Model\Element\AbstractElement $object
     */
    public function setRelatedObject(AbstractElement $object);

    /**
     * @return \Pimcore\Model\Element\AbstractElement|null
     */
    public function getSubNavigation(): ?AbstractElement;

    /**
     * @param \Pimcore\Model\Element\AbstractElement $subNavigation
     */
    public function setSubNavigation(AbstractElement $subNavigation);
}
