<?php

namespace PimcoreHeadlessContentBundle\Model;

use PimcoreHeadlessContentBundle\Model\OpenDxp\ResourceInterface;
use OpenDxp\Model\Element\AbstractElement;

interface NavigationLinkItemInterface extends ResourceInterface
{
    /**
     * @return \OpenDxp\Model\Element\AbstractElement|null
     */
    public function getRelatedObject(): ?AbstractElement;

    /**
     * @param \OpenDxp\Model\Element\AbstractElement $object
     */
    public function setRelatedObject(AbstractElement $object);

    /**
     * @return \OpenDxp\Model\Element\AbstractElement|null
     */
    public function getSubNavigation(): ?AbstractElement;

    /**
     * @param \OpenDxp\Model\Element\AbstractElement $subNavigation
     */
    public function setSubNavigation(AbstractElement $subNavigation);

    /**
     * @return bool|null
     */
    public function getIsPartner(): ?bool;

    /**
     * @param bool|null $isPartner
     */
    public function setIsPartner(?bool $isPartner);
}
