<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- heading [input]
 * -- items [block]
 * --- question [input]
 * --- linkType [select] anchor|external|none
 * --- linkValue [input]
 */

return \OpenDxp\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'ContactFaqShortcuts',
   'parentClass' => '',
   'implementsInterfaces' => '',
   'title' => '',
   'group' => 'Contact',
   'layoutDefinitions' =>
  \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => NULL, 'type' => NULL, 'region' => NULL, 'title' => NULL, 'width' => 0, 'height' => 0, 'collapsible' => false, 'collapsed' => false, 'bodyStyle' => NULL, 'datatype' => 'layout', 'permissions' => NULL,
     'children' =>
    array (
      0 =>
      \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'name' => 'Layout', 'type' => NULL, 'region' => NULL, 'title' => '', 'width' => '', 'height' => '', 'collapsible' => false, 'collapsed' => false, 'bodyStyle' => '', 'datatype' => 'layout', 'permissions' => NULL,
         'children' =>
        array (
          0 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'name' => 'localizedfields', 'title' => '', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => true, 'visibleSearch' => true, 'blockedVarsForExport' => array(),
             'children' =>
            array (
              0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'heading', 'title' => 'Heading', 'tooltip' => 'e.g. "Časté otázky"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
              )),
              1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'name' => 'items', 'title' => 'FAQ shortcuts', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                 'lazyLoading' => false, 'disallowAddRemove' => false, 'disallowReorder' => false, 'collapsible' => false, 'collapsed' => false, 'maxItems' => NULL, 'styleElement' => '',
                 'children' => array(
                   0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'question', 'title' => 'Question', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
                   1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                     'name' => 'linkType', 'title' => 'Link type', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                     'options' => array(
                       0 => array('key' => 'none', 'value' => 'none'),
                       1 => array('key' => 'anchor', 'value' => 'anchor'),
                       2 => array('key' => 'external', 'value' => 'external'),
                     ),
                     'defaultValue' => 'none', 'columnLength' => 50, 'dynamicOptions' => false, 'defaultValueGenerator' => '', 'width' => '', 'optionsProviderType' => 'configure', 'optionsProviderClass' => '', 'optionsProviderData' => '',
                   )),
                   2 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'linkValue', 'title' => 'Link value', 'tooltip' => 'anchor like "#filter-help" or full URL', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
                 ),
                 'layout' => NULL, 'referencedFields' => array(), 'fieldDefinitionsCache' => NULL,
              )),
            ),
             'region' => NULL, 'layout' => NULL, 'maxTabs' => NULL, 'border' => false, 'provideSplitView' => false, 'tabPosition' => 'top', 'hideLabelsWhenTabsReached' => NULL, 'referencedFields' => array(), 'permissionView' => NULL, 'permissionEdit' => NULL, 'labelWidth' => 0, 'labelAlign' => 'left', 'width' => '', 'height' => '', 'fieldDefinitionsCache' => NULL,
          )),
        ),
         'locked' => false, 'blockedVarsForExport' => array(), 'fieldtype' => 'panel', 'layout' => NULL, 'border' => false, 'icon' => '', 'labelWidth' => 0, 'labelAlign' => 'left',
      )),
    ),
     'locked' => false, 'blockedVarsForExport' => array(), 'fieldtype' => 'panel', 'layout' => NULL, 'border' => false, 'icon' => NULL, 'labelWidth' => 100, 'labelAlign' => 'left',
  )),
   'fieldDefinitionsCache' => NULL,
   'blockedVarsForExport' => array(),
   'activeDispatchingEvents' => array(),
));
