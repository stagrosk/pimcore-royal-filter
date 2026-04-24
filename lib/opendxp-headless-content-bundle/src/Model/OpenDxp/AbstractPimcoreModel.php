<?php

declare(strict_types=1);

namespace OpendxpHeadlessContentBundle\Model\OpenDxp;

use OpenDxp\Model\DataObject\Concrete;

abstract class AbstractOpendxpModel extends Concrete implements ResourceInterface, OpendxpModelInterface
{
}
