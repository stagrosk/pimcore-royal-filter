<?php

/**
 * Fields Summary:
 * - submitEndpoint [input] (non-localized POST URL, optional)
 * - localizedfields [localizedfields]
 * -- heading [input]
 * -- subheading [textarea]
 * -- nameLabel [input]
 * -- namePlaceholder [input]
 * -- emailLabel [input]
 * -- emailPlaceholder [input]
 * -- phoneLabel [input]
 * -- phonePlaceholder [input]
 * -- subjectLabel [input]
 * -- subjects [block] optionValue+label
 * -- messageLabel [input]
 * -- messagePlaceholder [input]
 * -- submitLabel [input]
 * -- successHeading [input]
 * -- successMessage [textarea]
 */

return \OpenDxp\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'ContactForm',
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
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'submitEndpoint', 'title' => 'Submit POST endpoint URL', 'tooltip' => 'optional', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
          )),
          1 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'name' => 'localizedfields', 'title' => '', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => true, 'visibleSearch' => true, 'blockedVarsForExport' => array(),
             'children' =>
            array (
              0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'heading', 'title' => 'Heading', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array('name' => 'subheading', 'title' => 'Subheading', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => '', 'width' => '')),
              2 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'nameLabel', 'title' => 'Name field label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              3 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'namePlaceholder', 'title' => 'Name field placeholder', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              4 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'emailLabel', 'title' => 'Email field label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              5 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'emailPlaceholder', 'title' => 'Email field placeholder', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              6 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'phoneLabel', 'title' => 'Phone field label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              7 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'phonePlaceholder', 'title' => 'Phone field placeholder', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              8 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'subjectLabel', 'title' => 'Subject field label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              9 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'name' => 'subjects', 'title' => 'Subject options', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                 'lazyLoading' => false, 'disallowAddRemove' => false, 'disallowReorder' => false, 'collapsible' => false, 'collapsed' => false, 'maxItems' => NULL, 'styleElement' => '',
                 'children' => array(
                   0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'optionValue', 'title' => 'Value (form key)', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 100, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
                   1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'label', 'title' => 'Label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
                 ),
                 'layout' => NULL, 'referencedFields' => array(), 'fieldDefinitionsCache' => NULL,
              )),
              10 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'messageLabel', 'title' => 'Message field label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              11 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'messagePlaceholder', 'title' => 'Message field placeholder', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              12 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'submitLabel', 'title' => 'Submit button label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 100, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              13 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array('name' => 'successHeading', 'title' => 'Success heading', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '')),
              14 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array('name' => 'successMessage', 'title' => 'Success message', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => '', 'width' => '')),
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
