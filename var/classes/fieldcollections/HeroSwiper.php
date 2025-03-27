<?php

/**
 * Fields Summary:
 * - localizedfields [localizedfields]
 * -- slides [block]
 * --- title [input]
 * --- image [image]
 * --- text [wysiwyg]
 * --- href [manyToOneRelation]
 * --- linkText [input]
 * --- externalLink [input]
 */

return \Pimcore\Model\DataObject\Fieldcollection\Definition::__set_state(array(
   'dao' => NULL,
   'key' => 'HeroSwiper',
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
          \Pimcore\Model\DataObject\ClassDefinition\Data\Localizedfields::__set_state(array(
             'fieldtype' => 'localizedfields',
             'children' =>
            array (
              0 =>
              \Pimcore\Model\DataObject\ClassDefinition\Data\Block::__set_state(array(
                 'fieldtype' => 'block',
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
                     'name' => 'title',
                     'title' => 'Title',
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
                  1 =>
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Image::__set_state(array(
                     'fieldtype' => 'image',
                     'name' => 'image',
                     'title' => 'Image',
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
                  2 =>
                  \Pimcore\Model\DataObject\ClassDefinition\Data\Wysiwyg::__set_state(array(
                     'fieldtype' => 'wysiwyg',
                     'width' => '',
                     'height' => '',
                     'toolbarConfig' => '',
                     'excludeFromSearchIndex' => false,
                     'maxCharacters' => '',
                     'name' => 'text',
                     'title' => 'Text',
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
                  3 =>
                  \Pimcore\Model\DataObject\ClassDefinition\Data\ManyToOneRelation::__set_state(array(
                     'fieldtype' => 'manyToOneRelation',
                     'width' => '',
                     'assetUploadPath' => '',
                     'relationType' => true,
                     'objectsAllowed' => true,
                     'assetsAllowed' => false,
                     'assetTypes' =>
                    array (
                    ),
                     'documentsAllowed' => false,
                     'documentTypes' =>
                    array (
                    ),
                     'classes' =>
                    array (
                      0 =>
                      array (
                        'classes' => 'ContentPage',
                      ),
                    ),
                     'pathFormatterClass' => '',
                     'name' => 'href',
                     'title' => 'Button Link',
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
                  4 =>
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
                     'name' => 'linkText',
                     'title' => 'Button Text',
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
                  5 =>
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
                     'name' => 'externalLink',
                     'title' => 'External Link',
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
                 'layout' => NULL,
                 'referencedFields' =>
                array (
                ),
                 'fieldDefinitionsCache' => NULL,
                 'name' => 'slides',
                 'title' => 'Slides',
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
