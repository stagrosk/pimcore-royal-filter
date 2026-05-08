<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- badgeLabel [input]
 * -- heading [input]
 * -- subheading [textarea]
 * -- milestones [block] year+title+desc
 */

return \OpenDxp\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'AboutTimeline',
   'parentClass' => '',
   'implementsInterfaces' => '',
   'title' => '',
   'group' => 'About',
   'layoutDefinitions' =>
  \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => NULL, 'type' => NULL, 'region' => NULL, 'title' => NULL, 'width' => 0, 'height' => 0, 'collapsible' => false, 'collapsed' => false, 'bodyStyle' => NULL, 'datatype' => 'layout', 'permissions' => NULL,
     'children' => array (
      0 => \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
         'name' => 'Layout', 'type' => NULL, 'region' => NULL, 'title' => '', 'width' => '', 'height' => '', 'collapsible' => false, 'collapsed' => false, 'bodyStyle' => '', 'datatype' => 'layout', 'permissions' => NULL,
         'children' => array (
          0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'name' => 'localizedfields', 'title' => '', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => true, 'visibleSearch' => true, 'blockedVarsForExport' => array(),
             'children' => array (
              0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'badgeLabel', 'title' => 'Badge label', 'tooltip' => 'e.g. "Náš príbeh"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'heading', 'title' => 'Heading', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              2 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array('name' => 'subheading', 'title' => 'Subheading', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => '', 'width' => '')),
              3 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'name' => 'milestones', 'title' => 'Milestones', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                 'lazyLoading' => false, 'disallowAddRemove' => false, 'disallowReorder' => false, 'collapsible' => true, 'collapsed' => false, 'maxItems' => NULL, 'styleElement' => '',
                 'children' => array(
                   0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'year', 'title' => 'Year', 'tooltip' => 'e.g. "2020"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 10, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
                   1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'title', 'title' => 'Title', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
                   2 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array('name' => 'desc', 'title' => 'Description', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => '', 'width' => '')),
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
