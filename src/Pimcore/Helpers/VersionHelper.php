<?php

namespace App\Pimcore\Helpers;

use OpenDxp\Model\DataObject\Concrete;
use OpenDxp\Model\Version;

class VersionHelper
{
    /**
     * This function enables usage of versioning in Pimcore and resets the state of versioning automatically
     * after your functions is finished.
     *
     * @param \Closure $function
     * @param bool     $enabled
     *
     * @return mixed
     */
    public static function useVersioning(\Closure $function, bool $enabled = true): mixed
    {
        $backup = Version::$disabled;

        if ($enabled) {
            Version::enable();
        } else {
            Version::disable();
        }

        $result = $function();

        if ($backup) {
            Version::disable();
        } else {
            Version::enable();
        }

        return $result;
    }

    /**
     * @param \Pimcore\Model\DataObject\Concrete $object
     *
     * @return \Pimcore\Model\DataObject\Concrete
     */
    public static function getLatestVersion(Concrete $object): Concrete
    {
        $latestVersion = $object->getLatestVersion();
        if ($latestVersion) {
            $latestObj = $latestVersion->loadData();
            if ($latestObj instanceof Concrete) {
                $object = $latestObj;
            }
        }

        return $object;
    }
}
