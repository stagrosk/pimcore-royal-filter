<?php

declare(strict_types=1);

namespace PimcoreHeadlessContentBundle\Model\OpenDxp;

use OpenDxp\Model\DataObject\Concrete;

abstract class AbstractPimcoreModel extends Concrete implements ResourceInterface, PimcoreModelInterface
{
}
