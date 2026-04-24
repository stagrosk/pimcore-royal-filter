<?php

namespace OpendxpHeadlessContentBundle\Model;

use OpendxpHeadlessContentBundle\Model\OpenDxp\OpendxpModelInterface;
use OpenDxp\Model\DataObject\Fieldcollection;

interface NavigationInterface extends OpendxpModelInterface
{
    /**
     * @return string|null
     */
    public function getIdentifier(): ?string;

    /**
     * @param string|null $identifier
     *
     * @return $this
     */
    public function setIdentifier(?string $identifier): static;

    /**
     * @return \OpenDxp\Model\DataObject\Fieldcollection|null
     */
    public function getLinks(): ?Fieldcollection;

    /**
     * @param \OpenDxp\Model\DataObject\Fieldcollection|null $links
     *
     * @return $this
     */
    public function setLinks(?Fieldcollection $links): static;

    /**
     * @return \OpendxpHeadlessContentBundle\Model\NavigationLinkItemInterface[]
     */
    public function getLinkItems(): array;

    /**
     * @param array $linkItems
     *
     * @return void
     */
    public function setLinkItems(array $linkItems): void;
}
