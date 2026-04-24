<?php

/**
 * Fields Summary:
 * - parameterGroup [select]
 * - parameterKey [select]
 * - filterType [select]
 * - unit [select]
 * - sortOrder [numeric]
 * - isExpanded [checkbox]
 * - localizedfields [localizedfields]
 * -- label [input]
 */

return \OpenDxp\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'FilterConfigItem',
   'parentClass' => '',
   'implementsInterfaces' => '',
   'title' => '',
   'group' => '',
   'layoutDefinitions' =>
  \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
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
     'children' =>
    array (
      0 =>
      \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
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
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'parameterGroup',
             'title' => 'Parameter Group',
             'tooltip' => 'Classification Store group identifier (e.g., "whirlpool", "dimensions")',
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
             'options' =>
            array (
            ),
             'defaultValue' => NULL,
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => '',
             'optionsProviderType' => 'class',
             'optionsProviderClass' => 'App\\OpenDxp\\Model\\OptionProviders\\ClassificationStoreGroupOptionsProvider',
             'optionsProviderData' => '',
          )),
          1 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'parameterKey',
             'title' => 'Parameter Key',
             'tooltip' => 'Classification Store key identifier (e.g., "height", "diameter")',
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
             'options' =>
            array (
            ),
             'defaultValue' => NULL,
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => '',
             'optionsProviderType' => 'class',
             'optionsProviderClass' => 'App\\OpenDxp\\Model\\OptionProviders\\ClassificationStoreKeyOptionsProvider',
             'optionsProviderData' => '',
          )),
          2 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'filterType',
             'title' => 'Filter Type',
             'tooltip' => 'Widget type for this filter',
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
             'options' =>
            array (
              0 =>
              array (
                'key' => 'Checkbox',
                'value' => 'checkbox',
              ),
              1 =>
              array (
                'key' => 'Range',
                'value' => 'range',
              ),
              2 =>
              array (
                'key' => 'Boolean',
                'value' => 'boolean',
              ),
            ),
             'defaultValue' => 'checkbox',
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => '',
             'optionsProviderType' => 'configure',
             'optionsProviderClass' => '',
             'optionsProviderData' => '',
          )),
          3 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'unit',
             'title' => 'Unit',
             'tooltip' => 'Display unit override. If empty, uses unit from parameter data',
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
            ),
             'defaultValue' => NULL,
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => '',
             'optionsProviderType' => 'class',
             'optionsProviderClass' => 'App\\OpenDxp\\Model\\OptionProviders\\UnitOptionsProvider',
             'optionsProviderData' => '',
          )),
          4 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Numeric::__set_state(array(
             'name' => 'sortOrder',
             'title' => 'Sort Order',
             'tooltip' => 'Display order in filter panel (lower = higher)',
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
             'defaultValue' => NULL,
             'integer' => true,
             'unsigned' => false,
             'minValue' => NULL,
             'maxValue' => NULL,
             'unique' => false,
             'decimalSize' => NULL,
             'decimalPrecision' => NULL,
             'width' => '',
             'defaultValueGenerator' => '',
          )),
          5 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
             'name' => 'isExpanded',
             'title' => 'Is Expanded',
             'tooltip' => 'Whether the filter section is expanded by default',
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
             'defaultValue' => 1,
             'defaultValueGenerator' => '',
          )),
          6 =>
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
                 'name' => 'label',
                 'title' => 'Label',
                 'tooltip' => 'Custom label override. If empty, uses the parameter\'s translated name',
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
                 'defaultValue' => NULL,
                 'columnLength' => 190,
                 'regex' => '',
                 'regexFlags' =>
                array (
                ),
                 'unique' => false,
                 'showCharCount' => false,
                 'width' => '',
                 'defaultValueGenerator' => '',
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
   'fieldDefinitionsCache' => NULL,
   'blockedVarsForExport' =>
  array (
  ),
   'activeDispatchingEvents' =>
  array (
  ),
));
