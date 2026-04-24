<?php

declare(strict_types=1);

namespace OpendxpHeadlessContentBundle\Exception;

class ImplementedByOpenDxpException extends \InvalidArgumentException
{
    /**
     * @param mixed $class
     * @param mixed $property
     */
    public function __construct($class, $property)
    {
        parent::__construct(
            sprintf(
                '%s of "%s" needs to be implemented by OpenDXP.',
                $class,
                $property
            )
        );
    }
}
