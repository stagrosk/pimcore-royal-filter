<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- heading [input]
 * -- items [block]
 * --- badgeLabel [input]
 * --- badgeColor [select] green|blue|amber|red|gray
 * --- iconName [input]
 * --- iconColor [select] primary|blue|green|amber|red
 * --- title [input]
 * --- subtitle [input]
 * --- mapEmbedUrl [textarea]
 * --- addressStreet [input]
 * --- addressCityZip [input]
 * --- addressLabel [input]
 * --- openingHoursTitle [input]
 * --- openingHours [textarea] one row per line: "label=value[=accent]"
 * --- businessInfoTitle [input]
 * --- businessInfo [textarea] one row per line: "label=value"
 * --- phone [input]
 * --- website [input]
 * --- websiteLabel [input]
 * --- navigationUrl [input]
 * --- navigationLabel [input]
 * --- primaryButton [checkbox]
 */

return \OpenDxp\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'ContactLocations',
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
              0 =>
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'heading', 'title' => 'Section heading', 'tooltip' => 'e.g. "Naše prevádzky"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => NULL, 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
              )),
              1 =>
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'name' => 'items', 'title' => 'Locations', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                 'lazyLoading' => false, 'disallowAddRemove' => false, 'disallowReorder' => false, 'collapsible' => true, 'collapsed' => false, 'maxItems' => NULL, 'styleElement' => '',
                 'children' =>
                array (
                  0 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'badgeLabel', 'title' => 'Badge label', 'tooltip' => 'e.g. "Prevádzka", "Sídlo firmy"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 100, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  1 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                     'name' => 'badgeColor', 'title' => 'Badge dot color', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                     'options' => array(
                       0 => array('key' => 'green', 'value' => 'green'),
                       1 => array('key' => 'blue', 'value' => 'blue'),
                       2 => array('key' => 'amber', 'value' => 'amber'),
                       3 => array('key' => 'red', 'value' => 'red'),
                       4 => array('key' => 'gray', 'value' => 'gray'),
                     ),
                     'defaultValue' => 'green', 'columnLength' => 50, 'dynamicOptions' => false, 'defaultValueGenerator' => '', 'width' => '', 'optionsProviderType' => 'configure', 'optionsProviderClass' => '', 'optionsProviderData' => '',
                  )),
                  2 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'iconName', 'title' => 'Header icon (Lucide)', 'tooltip' => 'e.g. Building2, FileText', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 100, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  3 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                     'name' => 'iconColor', 'title' => 'Header icon color', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(),
                     'options' => array(
                       0 => array('key' => 'primary', 'value' => 'primary'),
                       1 => array('key' => 'blue', 'value' => 'blue'),
                       2 => array('key' => 'green', 'value' => 'green'),
                       3 => array('key' => 'amber', 'value' => 'amber'),
                       4 => array('key' => 'red', 'value' => 'red'),
                     ),
                     'defaultValue' => 'primary', 'columnLength' => 50, 'dynamicOptions' => false, 'defaultValueGenerator' => '', 'width' => '', 'optionsProviderType' => 'configure', 'optionsProviderClass' => '', 'optionsProviderData' => '',
                  )),
                  4 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'title', 'title' => 'Title', 'tooltip' => 'e.g. "Prevádzka Brno"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  5 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'subtitle', 'title' => 'Subtitle', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  6 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                     'name' => 'mapEmbedUrl', 'title' => 'Google Maps embed URL', 'tooltip' => 'iframe src URL', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => 80, 'width' => '',
                  )),
                  7 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'addressStreet', 'title' => 'Address — street', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  8 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'addressCityZip', 'title' => 'Address — ZIP & city', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  9 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'openingHoursTitle', 'title' => 'Opening hours title', 'tooltip' => 'e.g. "Otváracie hodiny"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 100, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  10 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                     'name' => 'openingHours', 'title' => 'Opening hours', 'tooltip' => 'one row per line: "label=value" or "label=value=accent" where accent is normal|red|green', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => 100, 'width' => '',
                  )),
                  11 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'businessInfoTitle', 'title' => 'Business info title', 'tooltip' => 'e.g. "Firemné údaje"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 100, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  12 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                     'name' => 'businessInfo', 'title' => 'Business info rows', 'tooltip' => 'one row per line: "label=value", e.g. "IČO=55 403 590"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'maxLength' => NULL, 'showCharCount' => false, 'excludeFromSearchIndex' => false, 'height' => 100, 'width' => '',
                  )),
                  13 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'phone', 'title' => 'Phone (raw)', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 50, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  14 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'website', 'title' => 'Website URL', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 255, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  15 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'websiteLabel', 'title' => 'Website display label', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  16 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'navigationUrl', 'title' => 'Navigation URL (Google Maps)', 'tooltip' => '', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 500, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  17 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'navigationLabel', 'title' => 'Navigation button label', 'tooltip' => 'e.g. "Navigovať na prevádzku"', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => '', 'columnLength' => 190, 'regex' => '', 'regexFlags' => array(), 'unique' => false, 'showCharCount' => false, 'width' => '', 'defaultValueGenerator' => '',
                  )),
                  18 => \OpenDxp\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
                     'name' => 'primaryButton', 'title' => 'Primary button styling', 'tooltip' => 'true = teal CTA, false = gray ghost', 'mandatory' => false, 'noteditable' => false, 'index' => false, 'locked' => false, 'style' => '', 'permissions' => NULL, 'relationType' => false, 'invisible' => false, 'visibleGridView' => false, 'visibleSearch' => false, 'blockedVarsForExport' => array(), 'defaultValue' => 1, 'defaultValueGenerator' => '',
                  )),
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
