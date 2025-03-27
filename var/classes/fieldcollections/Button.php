<?php

/**
 * Fields Summary:
 * - color [rgbaColor]
 * - isExternal [checkbox]
 * - localizedfields [localizedfields]
 * -- link [link]
 * - position [select]
 * - fullWidth [checkbox]
 */

return \Pimcore\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'Button',
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
      \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'fieldtype' => 'panel',
         'layout' => NULL,
         'border' => false,
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
          \Pimcore\Model\DataObject\ClassDefinition\Data\RgbaColor::__set_state(array(
             'fieldtype' => 'rgbaColor',
             'width' => '',
             'name' => 'color',
             'title' => 'Color',
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
          )),
          1 =>
          \Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
             'fieldtype' => 'checkbox',
             'defaultValue' => NULL,
             'name' => 'isExternal',
             'title' => 'Is External',
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
          \Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'fieldtype' => 'localizedfields',
             'children' =>
            array (
              0 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\Link::__set_state(array(
                 'fieldtype' => 'link',
                 'name' => 'link',
                 'title' => 'Link',
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
          3 =>
          \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'fieldtype' => 'select',
             'options' =>
            array (
              0 =>
              array (
                'key' => 'left',
                'value' => 'left',
              ),
              1 =>
              array (
                'key' => 'center',
                'value' => 'center',
              ),
              2 =>
              array (
                'key' => 'right',
                'value' => 'right',
              ),
            ),
             'width' => '',
             'defaultValue' => 'left',
             'optionsProviderClass' => '',
             'optionsProviderData' => '',
             'columnLength' => 190,
             'dynamicOptions' => false,
             'name' => 'position',
             'title' => 'Position',
             'tooltip' => '',
             'mandatory' => true,
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
          4 =>
          \Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
             'fieldtype' => 'checkbox',
             'defaultValue' => 0,
             'name' => 'fullWidth',
             'title' => 'Full Width',
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
