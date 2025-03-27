<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- headline [input]
 * - whiteHeadline [checkbox]
 * - whiteBar [checkbox]
 * - news [manyToManyRelation]
 * - backgroundImage [image]
 */

return \Pimcore\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'FeaturedNews',
   'parentClass' => '',
   'implementsInterfaces' => '',
   'title' => '',
   'group' => 'Content',
   'layoutDefinitions' =>
  \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'fieldtype' => 'panel',
     'layout' => NULL,
     'border' => false,
     'name' => NULL,
     'type' => NULL,
     'region' => NULL,
     'title' => NULL,
     'width' => 0,
     'height' => 0,
     'collapsible' => false,
     'collapsed' => false,
     'bodyStyle' => NULL,
     'datatype' => 'layout',
     'permissions' => NULL,
     'children' =>
    array (
      0 =>
      \Pimcore\Model\DataObject\ClassDefinition\Layout\Tabpanel::__set_state(array(
         'fieldtype' => 'tabpanel',
         'border' => false,
         'tabPosition' => NULL,
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
         'permissions' => NULL,
         'children' =>
        array (
          0 =>
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'layout' => NULL,
             'border' => false,
             'name' => 'Überschrift',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Überschrift',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'permissions' => NULL,
             'children' =>
            array (
              0 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
                 'fieldtype' => 'localizedfields',
                 'children' =>
                array (
                  0 =>
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'fieldtype' => 'input',
                     'width' => '',
                     'defaultValue' => NULL,
                     'columnLength' => 190,
                     'regex' => '',
                     'regexFlags' =>
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'name' => 'headline',
                     'title' => 'Überschrift',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'datatype' => 'data',
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' =>
                    array (
                    ),
                     'defaultValueGenerator' => '',
                  )),
                ),
                 'name' => 'localizedfields',
                 'region' => NULL,
                 'layout' => NULL,
                 'title' => '',
                 'width' => '',
                 'height' => '',
                 'maxTabs' => NULL,
                 'border' => false,
                 'provideSplitView' => false,
                 'tabPosition' => NULL,
                 'hideLabelsWhenTabsReached' => NULL,
                 'referencedFields' =>
                array (
                ),
                 'fieldDefinitionsCache' => NULL,
                 'permissionView' => NULL,
                 'permissionEdit' => NULL,
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => NULL,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => true,
                 'visibleSearch' => true,
                 'blockedVarsForExport' =>
                array (
                ),
                 'labelWidth' => 0,
                 'labelAlign' => 'left',
              )),
              1 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                 'fieldtype' => 'checkbox',
                 'defaultValue' => NULL,
                 'name' => 'whiteHeadline',
                 'title' => 'Überschrift weiß darstellen',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' =>
                array (
                ),
                 'defaultValueGenerator' => '',
              )),
              2 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                 'fieldtype' => 'checkbox',
                 'defaultValue' => NULL,
                 'name' => 'whiteBar',
                 'title' => 'Balken über der Überschrift weiß darstellen',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' =>
                array (
                ),
                 'defaultValueGenerator' => '',
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' =>
            array (
            ),
             'icon' => '',
             'labelWidth' => 0,
             'labelAlign' => 'left',
          )),
          1 =>
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'layout' => NULL,
             'border' => false,
             'name' => 'News',
             'type' => NULL,
             'region' => NULL,
             'title' => 'News',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'permissions' => NULL,
             'children' =>
            array (
              0 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\ManyToManyRelation::__set_state(array(
                 'fieldtype' => 'manyToManyRelation',
                 'width' => '',
                 'height' => '',
                 'maxItems' => NULL,
                 'assetUploadPath' => '',
                 'relationType' => true,
                 'objectsAllowed' => true,
                 'assetsAllowed' => false,
                 'assetTypes' =>
                array (
                  0 =>
                  array (
                    'assetTypes' => '',
                  ),
                ),
                 'documentsAllowed' => false,
                 'documentTypes' =>
                array (
                  0 =>
                  array (
                    'documentTypes' => '',
                  ),
                ),
                 'enableTextSelection' => false,
                 'classes' =>
                array (
                  0 =>
                  array (
                    'classes' => 'NewsPage',
                  ),
                ),
                 'pathFormatterClass' => '',
                 'name' => 'news',
                 'title' => 'News',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' =>
                array (
                ),
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' =>
            array (
            ),
             'icon' => '',
             'labelWidth' => 0,
             'labelAlign' => 'left',
          )),
          2 =>
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'fieldtype' => 'panel',
             'layout' => NULL,
             'border' => false,
             'name' => 'Hintergrund',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Hintergrund',
             'width' => '',
             'height' => '',
             'collapsible' => false,
             'collapsed' => false,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'permissions' => NULL,
             'children' =>
            array (
              0 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                 'fieldtype' => 'image',
                 'name' => 'backgroundImage',
                 'title' => 'Hintergrund',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'datatype' => 'data',
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' =>
                array (
                ),
                 'width' => '',
                 'height' => '',
                 'uploadPath' => '',
              )),
            ),
             'locked' => false,
             'blockedVarsForExport' =>
            array (
            ),
             'icon' => '',
             'labelWidth' => 0,
             'labelAlign' => 'left',
          )),
        ),
         'locked' => false,
         'blockedVarsForExport' =>
        array (
        ),
      )),
    ),
     'locked' => false,
     'blockedVarsForExport' =>
    array (
    ),
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'generateTypeDeclarations' => true,
   'blockedVarsForExport' =>
  array (
  ),
));
