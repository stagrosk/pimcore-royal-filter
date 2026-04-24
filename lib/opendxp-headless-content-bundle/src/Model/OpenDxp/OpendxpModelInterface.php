<?php

declare(strict_types=1);

namespace OpendxpHeadlessContentBundle\Model\OpenDxp;

use OpenDxp\Model\DataObject\ClassDefinition;
use OpenDxp\Model\Element\ElementInterface;

interface OpendxpModelInterface extends ResourceInterface, ElementInterface
{
    /**
     * @param string $key
     *
     * @return $this
     */
    public function setKey(string $key): static;

    /**
     * @return string|null
     */
    public function getKey(): ?string;

    /**
     * @param bool $published
     */
    public function setPublished(bool $published): static;

    /**
     * @return bool
     */
    public function getPublished(): bool;

    /**
     * @return bool
     */
    public function isPublished(): bool;

    /**
     * @param \OpenDxp\Model\Element\ElementInterface|null $parent
     *
     * @return $this
     */
    public function setParent(?ElementInterface $parent): static;

    /**
     * @return \OpenDxp\Model\Element\ElementInterface|null
     */
    public function getParent(): ?ElementInterface;

    /**
     * @param string|null $field
     *
     * @return mixed
     */
    public function getObjectVar(?string $field): mixed;

    /**
     * @return mixed
     */
    public function save(array $parameters = []): static;

    /**
     * @return void
     */
    public function delete(): void;

    /**
     * @return \OpenDxp\Model\DataObject\ClassDefinition
     */
    public function getClass(): ClassDefinition;
}
