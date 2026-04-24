<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - deviceType [select]
 * - localizedfields [localizedfields]
 * -- title [input]
 * -- shortDescription [textarea]
 * -- description [wysiwyg]
 * - manufacturer [manyToOneRelation]
 * - metadata [classificationstore]
 * - whirlpoolProperties [objectbricks]
 * - extraParameters [fieldcollections]
 * - collection [manyToOneRelation]
 * - product [manyToOneRelation]
 * - generateAsProduct [checkbox]
 * - defaultImage [image]
 * - images [imageGallery]
 * - customerImages [imageGallery]
 * - downloads [manyToManyRelation]
 * - royalFilterSetups [fieldcollections]
 * - paperCartridges [manyToManyObjectRelation]
 */

return \OpenDxp\Model\DataObject\ClassDefinition::__set_state(array(
   'dao' => NULL,
   'id' => 'whirlpool',
   'name' => 'Whirlpool',
   'title' => '',
   'description' => '',
   'creationDate' => NULL,
   'modificationDate' => 1776427580,
   'userOwner' => 2,
   'userModification' => 2,
   'parentClass' => '',
   'implementsInterfaces' => '',
   'listingParentClass' => '',
   'useTraits' => '',
   'listingUseTraits' => '',
   'encryption' => false,
   'encryptedTables' => 
  array (
  ),
   'allowInherit' => false,
   'allowVariants' => false,
   'showVariants' => false,
   'layoutDefinitions' => 
  \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => 'pimcore_root',
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => 0,
     'height' => 0,
     'collapsible' => false,
     'collapsed' => false,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'children' => 
    array (
      0 => 
      \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'name' => 'Panel',
         'type' => NULL,
         'region' => '',
         'title' => '',
         'width' => '',
         'height' => '',
         'collapsible' => false,
         'collapsed' => false,
         'bodyStyle' => '',
         'datatype' => 'layout',
         'children' => 
        array (
          0 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Tabpanel::__set_state(array(
             'name' => 'Layout',
             'type' => NULL,
             'region' => NULL,
             'title' => '',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'children' => 
            array (
              0 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Whirlpool info',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Whirlpool info',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                     'name' => 'deviceType',
                     'title' => 'Device Type',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'options' => 
                    array (
                      0 => 
                      array (
                        'key' => 'SwimSpa',
                        'value' => 'swimspa',
                      ),
                      1 => 
                      array (
                        'key' => 'Whirlpool',
                        'value' => 'whirlpool',
                      ),
                    ),
                     'defaultValue' => 'whirlpool',
                     'columnLength' => 190,
                     'dynamicOptions' => false,
                     'defaultValueGenerator' => '',
                     'width' => '',
                     'optionsProviderType' => 'configure',
                     'optionsProviderClass' => '',
                     'optionsProviderData' => '',
                  )),
                  1 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                     'name' => 'localizedfields',
                     'title' => '',
                     'tooltip' => NULL,
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => NULL,
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => true,
                     'visibleSearch' => true,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'children' => 
                    array (
                      0 => 
                      \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                         'name' => 'title',
                         'title' => 'Title',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => true,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'defaultValue' => NULL,
                         'columnLength' => 190,
                         'regex' => '',
                         'regexFlags' => 
                        array (
                        ),
                         'unique' => false,
                         'showCharCount' => false,
                         'width' => 500,
                         'defaultValueGenerator' => '',
                      )),
                      1 => 
                      \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                         'name' => 'shortDescription',
                         'title' => 'Short Description',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'maxLength' => NULL,
                         'showCharCount' => true,
                         'excludeFromSearchIndex' => false,
                         'height' => 250,
                         'width' => 450,
                      )),
                      2 => 
                      \OpenDxp\Model\DataObject\ClassDefinition\Data\Wysiwyg::__set_state(array(
                         'name' => 'description',
                         'title' => 'Description',
                         'tooltip' => '',
                         'mandatory' => false,
                         'noteditable' => false,
                         'index' => false,
                         'locked' => false,
                         'style' => '',
                         'permissions' => NULL,
                         'fieldtype' => '',
                         'relationType' => false,
                         'invisible' => false,
                         'visibleGridView' => false,
                         'visibleSearch' => false,
                         'blockedVarsForExport' => 
                        array (
                        ),
                         'toolbarConfig' => '',
                         'excludeFromSearchIndex' => false,
                         'maxCharacters' => '',
                         'height' => 500,
                         'width' => 650,
                      )),
                    ),
                     'region' => NULL,
                     'layout' => NULL,
                     'maxTabs' => NULL,
                     'border' => false,
                     'provideSplitView' => false,
                     'tabPosition' => 'top',
                     'hideLabelsWhenTabsReached' => NULL,
                     'referencedFields' => 
                    array (
                    ),
                     'permissionView' => NULL,
                     'permissionEdit' => NULL,
                     'labelWidth' => 100,
                     'labelAlign' => 'left',
                     'width' => '',
                     'height' => '',
                     'fieldDefinitionsCache' => NULL,
                  )),
                  2 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                     'name' => 'manufacturer',
                     'title' => 'Manufacturer',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => true,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'classes' => 
                    array (
                      0 => 
                      array (
                        'classes' => 'Manufacturer',
                      ),
                    ),
                     'displayMode' => 'grid',
                     'pathFormatterClass' => '',
                     'assetInlineDownloadAllowed' => false,
                     'assetUploadPath' => '',
                     'allowToClearRelation' => true,
                     'objectsAllowed' => true,
                     'assetsAllowed' => false,
                     'assetTypes' => 
                    array (
                    ),
                     'documentsAllowed' => false,
                     'documentTypes' => 
                    array (
                    ),
                     'width' => 400,
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/database.svg',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
              1 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Parameters',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Parameters',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Classificationstore::__set_state(array(
                     'name' => 'metadata',
                     'title' => 'Metadata',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'children' => 
                    array (
                    ),
                     'labelWidth' => 0,
                     'localized' => true,
                     'storeId' => 1,
                     'hideEmptyData' => false,
                     'disallowAddRemove' => false,
                     'referencedFields' => 
                    array (
                    ),
                     'fieldDefinitionsCache' => NULL,
                     'allowedGroupIds' => 
                    array (
                    ),
                     'activeGroupDefinitions' => 
                    array (
                    ),
                     'maxItems' => NULL,
                     'height' => NULL,
                     'width' => NULL,
                  )),
                  1 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Objectbricks::__set_state(array(
                     'name' => 'whirlpoolProperties',
                     'title' => 'Whirlpool properties',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => true,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'allowedTypes' => 
                    array (
                      0 => 'whirlpoolProperties',
                    ),
                     'maxItems' => NULL,
                     'border' => false,
                  )),
                  2 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Fieldcollections::__set_state(array(
                     'name' => 'extraParameters',
                     'title' => 'Extra Parameters',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'allowedTypes' => 
                    array (
                      0 => 'parameter',
                    ),
                     'lazyLoading' => true,
                     'maxItems' => NULL,
                     'disallowAddRemove' => false,
                     'disallowReorder' => false,
                     'collapsed' => false,
                     'collapsible' => false,
                     'border' => false,
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/bricks.svg',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
              2 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Product',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Product',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                     'name' => 'collection',
                     'title' => 'Collection',
                     'tooltip' => '',
                     'mandatory' => true,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => true,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'classes' => 
                    array (
                      0 => 
                      array (
                        'classes' => 'Collection',
                      ),
                    ),
                     'displayMode' => 'grid',
                     'pathFormatterClass' => '',
                     'assetInlineDownloadAllowed' => false,
                     'assetUploadPath' => '',
                     'allowToClearRelation' => true,
                     'objectsAllowed' => true,
                     'assetsAllowed' => false,
                     'assetTypes' => 
                    array (
                    ),
                     'documentsAllowed' => false,
                     'documentTypes' => 
                    array (
                    ),
                     'width' => '500px',
                  )),
                  1 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                     'name' => 'product',
                     'title' => 'Product',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => true,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => true,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'classes' => 
                    array (
                      0 => 
                      array (
                        'classes' => 'Product',
                      ),
                    ),
                     'displayMode' => 'grid',
                     'pathFormatterClass' => '',
                     'assetInlineDownloadAllowed' => false,
                     'assetUploadPath' => '',
                     'allowToClearRelation' => true,
                     'objectsAllowed' => true,
                     'assetsAllowed' => false,
                     'assetTypes' => 
                    array (
                    ),
                     'documentsAllowed' => false,
                     'documentTypes' => 
                    array (
                    ),
                     'width' => '',
                  )),
                  2 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                     'name' => 'generateAsProduct',
                     'title' => 'Generate as product',
                     'tooltip' => 'On save or with command will be automatically generated as shopify product and sent to shopify',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValue' => 0,
                     'defaultValueGenerator' => '',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/package.svg',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
              3 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Assets',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Assets',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                     'name' => 'defaultImage',
                     'title' => 'Default Image',
                     'tooltip' => '',
                     'mandatory' => true,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'uploadPath' => '',
                     'width' => '',
                     'height' => '',
                  )),
                  1 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ImageGallery::__set_state(array(
                     'name' => 'images',
                     'title' => 'Images',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'uploadPath' => '',
                     'ratioX' => NULL,
                     'ratioY' => NULL,
                     'predefinedDataTemplates' => '',
                     'height' => '',
                     'width' => '',
                  )),
                  2 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ImageGallery::__set_state(array(
                     'name' => 'customerImages',
                     'title' => 'Customer images',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'uploadPath' => '',
                     'ratioX' => NULL,
                     'ratioY' => NULL,
                     'predefinedDataTemplates' => '',
                     'height' => '',
                     'width' => '',
                  )),
                  3 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToManyRelation::__set_state(array(
                     'name' => 'downloads',
                     'title' => 'Downloads',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => true,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'classes' => 
                    array (
                    ),
                     'displayMode' => NULL,
                     'pathFormatterClass' => '',
                     'maxItems' => NULL,
                     'assetInlineDownloadAllowed' => false,
                     'assetUploadPath' => '',
                     'allowToClearRelation' => true,
                     'objectsAllowed' => false,
                     'assetsAllowed' => true,
                     'assetTypes' => 
                    array (
                      0 => 
                      array (
                        'assetTypes' => 'unknown',
                      ),
                      1 => 
                      array (
                        'assetTypes' => 'text',
                      ),
                      2 => 
                      array (
                        'assetTypes' => 'archive',
                      ),
                      3 => 
                      array (
                        'assetTypes' => 'audio',
                      ),
                      4 => 
                      array (
                        'assetTypes' => 'document',
                      ),
                      5 => 
                      array (
                        'assetTypes' => 'video',
                      ),
                    ),
                     'documentsAllowed' => false,
                     'documentTypes' => 
                    array (
                      0 => 
                      array (
                        'documentTypes' => 'page',
                      ),
                    ),
                     'enableTextSelection' => false,
                     'width' => '',
                     'height' => '',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/image.svg',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
              4 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Filters setup',
                 'type' => NULL,
                 'region' => NULL,
                 'title' => 'Filters setup',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Fieldcollections::__set_state(array(
                     'name' => 'royalFilterSetups',
                     'title' => 'Royal filter setups',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'allowedTypes' => 
                    array (
                      0 => 'royalFilterSetup',
                    ),
                     'lazyLoading' => true,
                     'maxItems' => NULL,
                     'disallowAddRemove' => false,
                     'disallowReorder' => false,
                     'collapsed' => false,
                     'collapsible' => false,
                     'border' => false,
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '/bundles/pimcoreadmin/img/flat-color-icons/puzzle.svg',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
              5 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
                 'name' => 'Paper filter',
                 'type' => NULL,
                 'region' => '',
                 'title' => 'Paper filter',
                 'width' => '',
                 'height' => '',
                 'collapsible' => false,
                 'collapsed' => false,
                 'bodyStyle' => '',
                 'datatype' => 'layout',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToManyObjectRelation::__set_state(array(
                     'name' => 'paperCartridges',
                     'title' => 'Paper Cartridges',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'fieldtype' => '',
                     'relationType' => true,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'classes' => 
                    array (
                      0 => 
                      array (
                        'classes' => 'PaperCartridge',
                      ),
                    ),
                     'displayMode' => 'grid',
                     'pathFormatterClass' => '',
                     'maxItems' => NULL,
                     'visibleFields' => 'id,title,length,diameter,centerDiameter',
                     'allowToCreateNewObject' => false,
                     'allowToClearRelation' => true,
                     'optimizedAdminLoading' => true,
                     'enableTextSelection' => true,
                     'visibleFieldDefinitions' => 
                    array (
                    ),
                     'width' => 600,
                     'height' => '',
                  )),
                ),
                 'locked' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'fieldtype' => 'panel',
                 'layout' => NULL,
                 'border' => false,
                 'icon' => '',
                 'labelWidth' => 100,
                 'labelAlign' => 'left',
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' => 
            array (
            ),
             'fieldtype' => 'tabpanel',
             'border' => false,
             'tabPosition' => 'top',
          )),
        ),
         'locked' => false,
         'blockedVarsForExport' => 
        array (
        ),
         'fieldtype' => 'panel',
         'layout' => NULL,
         'border' => false,
         'icon' => '',
         'labelWidth' => 100,
         'labelAlign' => 'left',
      )),
    ),
     'locked' => false,
     'blockedVarsForExport' => 
    array (
    ),
     'fieldtype' => 'panel',
     'layout' => NULL,
     'border' => false,
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'icon' => '',
   'group' => '',
   'showAppLoggerTab' => false,
   'linkGeneratorReference' => '',
   'previewGeneratorReference' => '',
   'compositeIndices' => 
  array (
  ),
   'showFieldLookup' => false,
   'propertyVisibility' => 
  array (
    'grid' => 
    array (
      'id' => true,
      'key' => false,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
    'search' => 
    array (
      'id' => true,
      'key' => false,
      'path' => true,
      'published' => true,
      'modificationDate' => true,
      'creationDate' => true,
    ),
  ),
   'enableGridLocking' => false,
   'deletedDataComponents' => 
  array (
  ),
   'blockedVarsForExport' => 
  array (
  ),
   'fieldDefinitionsCache' => 
  array (
  ),
   'activeDispatchingEvents' => 
  array (
  ),
));
