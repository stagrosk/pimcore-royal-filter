<?php

declare(strict_types=1);

namespace OpendxpHeadlessContentBundle\Model\OpenDxp;

interface ResourceInterface
{
    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @param array $data
     *
     * @return $this
     */
    public function setValues(array $data = []): static;

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $ignoreEmptyValues
     *
     * @return $this
     */
    public function setValue(string $key, mixed $value, bool $ignoreEmptyValues = false): static;
}
