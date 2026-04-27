<?php

namespace App\Service\Generator;

use OpenDxp\Model\DataObject\AbstractObject;
use OpenDxp\Model\DataObject\Folder;
use OpenDxp\Model\DataObject\Service;

class ProductFolderResolver
{
    public const PRODUCTS_ROOT_FILTER = 'Products';
    public const PRODUCTS_ROOT_WHIRLPOOL = 'Products/Filters-by-whirlpools';

    /**
     * Mirror the source object's parent folder hierarchy under a new root.
     * The first segment of the source path is dropped and replaced by $targetRoot.
     *
     * Example:
     *   source path:  /RoyalFilterSetups/Filter Setups/Caldera Spas/Filter-X
     *   targetRoot:   Products
     *   result:       /Products/Filter Setups/Caldera Spas
     */
    public function resolveParentFolder(AbstractObject $source, string $targetRoot): Folder
    {
        // start from the object's own full path so we always see the real hierarchy
        $sourceParts = explode('/', trim($source->getFullPath(), '/'));
        array_pop($sourceParts);   // drop object's own key (the leaf)
        array_shift($sourceParts); // drop source root folder (e.g. "RoyalFilterSetups", "Whirlpools")

        $targetParts = explode('/', trim($targetRoot, '/'));
        $path = '/' . implode('/', array_merge($targetParts, $sourceParts));

        return Service::createFolderByPath($path);
    }
}
