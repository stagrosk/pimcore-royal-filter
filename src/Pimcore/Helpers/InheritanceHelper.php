<?php

namespace App\OpenDxp\Helpers;

use OpenDxp\Model\DataObject;

class InheritanceHelper
{
    /**
     * This function enables usage of inherited values in Pimcore and resets the state of inheritance automatically
     * after your functions is finished.
     *
     * @param \Closure $function
     * @param bool $inheritValues
     * @param bool $fallbackValues
     * @return mixed
     */
    public static function useInheritedValues(\Closure $function, bool $inheritValues = true, bool $fallbackValues = true): mixed
    {
        $backupInheritance = DataObject\AbstractObject::getGetInheritedValues();
        $backupFallback = DataObject\Localizedfield::getGetFallbackValues();
        DataObject\AbstractObject::setGetInheritedValues($inheritValues);
        DataObject\Localizedfield::setGetFallbackValues($fallbackValues);

        $result = $function();

        DataObject\AbstractObject::setGetInheritedValues($backupInheritance);
        DataObject\Localizedfield::setGetFallbackValues($backupFallback);

        return $result;
    }
}
