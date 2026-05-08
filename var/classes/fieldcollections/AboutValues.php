<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- badgeLabel [input]
 * -- heading [input]
 * -- items [block]
 * --- icon [select]
 * --- colorScheme [select]
 * --- title [input]
 * --- desc [textarea]
 */

return \OpenDxp\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'AboutValues',
   'parentClass' => '',
   'implementsInterfaces' => '',
   'title' => '',
   'group' => 'About',
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
     'permissions' => NULL,
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
         'permissions' => NULL,
         'children' => 
        array (
          0 => 
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'name' => 'localizedfields',
             'title' => '',
             'tooltip' => '',
             'mandatory' => false,
             'noteditable' => false,
             'index' => false,
             'locked' => false,
             'style' => '',
             'permissions' => NULL,
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
                 'name' => 'badgeLabel',
                 'title' => 'Badge label',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
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
              1 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'heading',
                 'title' => 'Heading',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'defaultValue' => NULL,
                 'columnLength' => 255,
                 'regex' => '',
                 'regexFlags' => 
                array (
                ),
                 'unique' => false,
                 'showCharCount' => false,
                 'width' => '',
                 'defaultValueGenerator' => '',
              )),
              2 => 
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'name' => 'items',
                 'title' => 'Values',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'relationType' => false,
                 'invisible' => false,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'lazyLoading' => false,
                 'disallowAddRemove' => false,
                 'disallowReorder' => false,
                 'collapsible' => false,
                 'collapsed' => false,
                 'maxItems' => NULL,
                 'styleElement' => '',
                 'children' => 
                array (
                  0 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                     'name' => 'icon',
                     'title' => 'Icon',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValue' => '',
                     'columnLength' => 100,
                     'dynamicOptions' => false,
                     'defaultValueGenerator' => '',
                     'width' => '',
                     'optionsProviderType' => 'class',
                     'optionsProviderClass' => 'App\\OpenDxp\\Model\\OptionProviders\\IconOptionsProvider',
                     'optionsProviderData' => '',
                  )),
                  1 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                     'name' => 'colorScheme',
                     'title' => 'Color scheme',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
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
                        'key' => 'blue',
                        'value' => 'blue',
                      ),
                      1 => 
                      array (
                        'key' => 'teal',
                        'value' => 'teal',
                      ),
                      2 => 
                      array (
                        'key' => 'amber',
                        'value' => 'amber',
                      ),
                      3 => 
                      array (
                        'key' => 'green',
                        'value' => 'green',
                      ),
                      4 => 
                      array (
                        'key' => 'rose',
                        'value' => 'rose',
                      ),
                      5 => 
                      array (
                        'key' => 'purple',
                        'value' => 'purple',
                      ),
                      6 => 
                      array (
                        'key' => 'red',
                        'value' => 'red',
                      ),
                    ),
                     'defaultValue' => 'blue',
                     'columnLength' => 50,
                     'dynamicOptions' => false,
                     'defaultValueGenerator' => '',
                     'width' => '',
                     'optionsProviderType' => 'configure',
                     'optionsProviderClass' => '',
                     'optionsProviderData' => '',
                  )),
                  2 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'title',
                     'title' => 'Title',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'defaultValue' => '',
                     'columnLength' => 255,
                     'regex' => '',
                     'regexFlags' => 
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'width' => '',
                     'defaultValueGenerator' => '',
                  )),
                  3 => 
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                     'name' => 'desc',
                     'title' => 'Description',
                     'tooltip' => '',
                     'mandatory' => false,
                     'noteditable' => false,
                     'index' => false,
                     'locked' => false,
                     'style' => '',
                     'permissions' => NULL,
                     'relationType' => false,
                     'invisible' => false,
                     'visibleGridView' => false,
                     'visibleSearch' => false,
                     'blockedVarsForExport' => 
                    array (
                    ),
                     'maxLength' => NULL,
                     'showCharCount' => false,
                     'excludeFromSearchIndex' => false,
                     'height' => '',
                     'width' => '',
                  )),
                ),
                 'layout' => NULL,
                 'referencedFields' => 
                array (
                ),
                 'fieldDefinitionsCache' => NULL,
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
             'labelWidth' => 0,
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
         'labelWidth' => 0,
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
