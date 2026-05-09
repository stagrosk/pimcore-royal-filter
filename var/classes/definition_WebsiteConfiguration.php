<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- brandTagline [input]
 * -- brandDescription [textarea]
 * -- welcomeMessage [input]
 * -- email [input]
 * -- phone [input]
 * -- addressStreet [input]
 * -- addressZip [input]
 * -- addressCity [input]
 * -- addressCountry [input]
 * -- registrationNote [input]
 * -- copyrightText [input]
 * - openingHours [fieldcollections]
 * - socialMedia [block]
 * -- socialMediaUrl [input]
 * -- socialMediaIcon [image]
 * - legalName [input]
 * - companyId [input]
 * - taxId [input]
 * - vatId [input]
 * - termsAndConditions [manyToOneRelation]
 * - personalDataProtection [manyToOneRelation]
 * - filterBenefitSet [manyToOneRelation]
 */

return \OpenDxp\Model\DataObject\ClassDefinition::__set_state(array(
   'dao' => NULL,
   'id' => 'website_configuration',
   'name' => 'WebsiteConfiguration',
   'title' => '',
   'description' => '',
   'creationDate' => NULL,
   'modificationDate' => 1777809883,
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
     'name' => 'opendxp_root',
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
         'permissions' => NULL,
         'children' =>
        array (
          0 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Brand',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Brand',
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
                     'name' => 'brandTagline',
                     'title' => 'Brand tagline',
                     'tooltip' => 'Krátky claim pod logom (napr. "PRANIE NAMIESTO PLYTVANIA").',
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
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
                     'name' => 'brandDescription',
                     'title' => 'Brand description',
                     'tooltip' => 'Krátky popisný odsek o značke vo footri.',
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
          1 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Kontakt',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Kontakt',
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Fieldcollections::__set_state(array(
                 'name' => 'openingHours',
                 'title' => 'Otváracie hodiny',
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
                 'allowedTypes' =>
                array (
                  0 => 'openingHours',
                ),
                 'lazyLoading' => true,
                 'maxItems' => NULL,
                 'disallowAddRemove' => false,
                 'disallowReorder' => false,
                 'collapsed' => false,
                 'collapsible' => false,
                 'border' => false,
              )),
              1 =>
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
                     'name' => 'welcomeMessage',
                     'title' => 'Welcome Message',
                     'tooltip' => 'Krátka uvítacia veta zobrazená v topbare.',
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
                     'name' => 'email',
                     'title' => 'Email',
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
                  2 =>
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'phone',
                     'title' => 'Phone',
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
                  3 =>
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'addressStreet',
                     'title' => 'Address street',
                     'tooltip' => 'Ulica a popisné číslo (napr. "Dobšinského 34").',
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
                  4 =>
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'addressZip',
                     'title' => 'Address ZIP',
                     'tooltip' => 'PSČ (napr. "953 01").',
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
                     'columnLength' => 20,
                     'regex' => '',
                     'regexFlags' =>
                    array (
                    ),
                     'unique' => false,
                     'showCharCount' => false,
                     'width' => '',
                     'defaultValueGenerator' => '',
                  )),
                  5 =>
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'addressCity',
                     'title' => 'Address city',
                     'tooltip' => 'Mesto (napr. "Zlaté Moravce").',
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
                  6 =>
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'addressCountry',
                     'title' => 'Address country',
                     'tooltip' => 'Krajina alebo ISO kód (napr. "SK", "Slovensko").',
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
          2 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Social Media',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Social Media',
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'name' => 'socialMedia',
                 'title' => 'Social Media',
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
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                     'name' => 'socialMediaUrl',
                     'title' => 'Social media url',
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
                  \OpenDxp\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                     'name' => 'socialMediaIcon',
                     'title' => 'Social media icon',
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
                     'uploadPath' => '',
                     'width' => '',
                     'height' => '',
                  )),
                ),
                 'layout' => NULL,
                 'referencedFields' =>
                array (
                ),
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
          3 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Prevadzkovatel',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Prevádzkovateľ',
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'legalName',
                 'title' => 'Legal name',
                 'tooltip' => 'Obchodné meno (napr. "STAGRO s.r.o.").',
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
                 'name' => 'companyId',
                 'title' => 'IČO',
                 'tooltip' => 'Identifikačné číslo organizácie.',
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
                 'columnLength' => 32,
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'taxId',
                 'title' => 'DIČ',
                 'tooltip' => 'Daňové identifikačné číslo.',
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
                 'columnLength' => 32,
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
                 'name' => 'vatId',
                 'title' => 'IČ DPH',
                 'tooltip' => 'Identifikačné číslo pre DPH (napr. "SK2022682860").',
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
                 'columnLength' => 32,
                 'regex' => '',
                 'regexFlags' =>
                array (
                ),
                 'unique' => false,
                 'showCharCount' => false,
                 'width' => '',
                 'defaultValueGenerator' => '',
              )),
              4 =>
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
                     'name' => 'registrationNote',
                     'title' => 'Registration note',
                     'tooltip' => 'Údaj o zápise v obchodnom registri (napr. "OR Nitra, oddiel: Sro, vložka 23086/N").',
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
             'labelWidth' => 100,
             'labelAlign' => 'left',
          )),
          4 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Bottom Bar',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Bottom Bar',
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
                     'name' => 'copyrightText',
                     'title' => 'Copyright text',
                     'tooltip' => 'Text spodnej lišty footera. Dostupné placeholdery: {year} (aktuálny rok).',
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
          5 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'ContentPages',
             'type' => NULL,
             'region' => NULL,
             'title' => 'ContentPages',
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                 'name' => 'termsAndConditions',
                 'title' => 'Terms and conditions',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
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
                    'classes' => 'ContentPage',
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
              1 =>
              \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                 'name' => 'personalDataProtection',
                 'title' => 'Personal data protection',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
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
                    'classes' => 'ContentPage',
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
          6 =>
          \OpenDxp\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Defaults',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Defaults',
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
              \OpenDxp\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                 'name' => 'filterBenefitSet',
                 'title' => 'Filter benefit set',
                 'tooltip' => '',
                 'mandatory' => false,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
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
                    'classes' => 'ProductBenefitSet',
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
     'icon' => NULL,
     'labelWidth' => 100,
     'labelAlign' => 'left',
  )),
   'icon' => '/bundles/opendxpadmin/img/flat-color-icons/automatic.svg',
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
