<?php

namespace App\OpenDxp\Model\DataObject;

use OpenDxp\Model\DataObject;
use OpenDxp\Model\DataObject\Exception\InheritanceParentNotFoundException;
use OpenDxp\Model\DataObject\Fieldcollection;
use OpenDxp\Model\DataObject\Service;

class RoyalFilter extends DataObject\RoyalFilter
{
    /**
     * @throws \Exception
     * @return \OpenDxp\Model\DataObject\Fieldcollection|null
     */
    public function getPrices(): ?Fieldcollection
    {
        $data = parent::getPrices();

        $inheritedData = Service::useInheritedValues(true, function() use ($data) {
            if (DataObject::doGetInheritedValues($this) && $this->getClass()->getFieldDefinition("Prices")->isEmpty($data)) {
                try {
                    return $this->getValueFromParent("Prices");
                } catch (InheritanceParentNotFoundException $e) {
                    return null;
                }
            }
            return null;
        });

        return $inheritedData ?? $data;
    }
}
