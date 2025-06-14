<?php

/**
 * Inheritance: no
 * Variants: no
 *
 * Fields Summary:
 * - classificationStoreIdent [input]
 * - apiId [input]
 * - name [input]
 * - metaKey [input]
 * - metaType [select]
 * - namespace [input]
 * - isPinned [checkbox]
 * - description [textarea]
 * - adminAccess [select]
 * - customerAccountAccess [select]
 * - storefrontAccess [select]
 * - ownerType [select]
 */

return \Pimcore\Model\DataObject\ClassDefinition::__set_state(array(
   'dao' => NULL,
   'id' => 'shopify_metafield_definition',
   'name' => 'ShopifyMetafieldDefinition',
   'title' => '',
   'description' => '',
   'creationDate' => NULL,
   'modificationDate' => 1749897397,
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
  \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
     'name' => 'pimcore_root',
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
      \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
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
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Text::__set_state(array(
             'name' => 'Layout',
             'type' => NULL,
             'region' => NULL,
             'title' => 'Meta types - description',
             'width' => '',
             'height' => '',
             'collapsible' => true,
             'collapsed' => true,
             'bodyStyle' => '',
             'datatype' => 'layout',
             'children' => 
            array (
            ),
             'locked' => false,
             'blockedVarsForExport' => 
            array (
            ),
             'fieldtype' => 'text',
             'html' => '<table style="transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; font-variant-numeric: normal; font-variant-east-asian: normal; font-variant-alternates: normal; font-size-adjust: none; font-kerning: auto; font-optical-sizing: auto; font-feature-settings: normal; font-variation-settings: normal; font-variant-position: normal; font-variant-emoji: normal; font-stretch: normal; font-size: 14px; line-height: 20px; font-family: Inter, -apple-system, BlinkMacSystemFont, &quot;San Francisco&quot;, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, sans-serif; border-collapse: collapse; width: 955.422px; color: rgb(215, 215, 219);"><thead style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; vertical-align: bottom; border-bottom: 1px solid rgb(36, 57, 61);"><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"><th style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; text-align: left; padding: 6px 12px; font-weight: normal; color: rgb(138, 143, 147); background-color: rgb(32, 41, 43);">Type</th><th style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; text-align: left; padding: 6px 12px; font-weight: normal; color: rgb(138, 143, 147); background-color: rgb(32, 41, 43);">Description</th><th style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; text-align: left; padding: 6px 12px; font-weight: normal; color: rgb(138, 143, 147); background-color: rgb(32, 41, 43);">Example value</th><th style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; text-align: left; padding: 6px 12px; font-weight: normal; color: rgb(138, 143, 147); background-color: rgb(32, 41, 43);">Value type</th><th style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; text-align: left; padding: 6px 12px; font-weight: normal; color: rgb(138, 143, 147); background-color: rgb(32, 41, 43);"><a href="https://shopify.dev/docs/api/admin-graphql/latest/mutations/translationsRegister" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);">Translatable</a></th><th style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; position: relative; text-align: left; padding: 6px 12px; font-weight: normal; color: rgb(138, 143, 147); background-color: rgb(32, 41, 43);"><a href="https://shopify.dev/docs/api/admin-graphql/latest/mutations/marketLocalizationsRegister" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);">Market localizable</a></th></tr></thead><tbody style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">boolean</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A true or false value.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">true</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">boolean</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">color</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">The hexadecimal code for a color.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">#fff123</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><a id="date" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">date</code></a></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A date in&nbsp;<a rel="noreferrer external" target="_blank" href="https://www.iso.org/iso-8601-date-and-time-format.html" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250); position: relative; margin-right: 20px;">ISO 8601</a>&nbsp;format without a presumed timezone.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">2022-02-02</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">date_time</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A date and time in&nbsp;<a rel="noreferrer external" target="_blank" href="https://www.iso.org/iso-8601-date-and-time-format.html" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250); position: relative; margin-right: 20px;">ISO 8601</a>&nbsp;format without a presumed timezone. Defaults to Greenwich Mean Time (GMT).</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">2024-01-01T12:30:00</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">dimension</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A value and a unit of length. Valid unit values:&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">in</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">ft</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">yd</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">mm</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">cm</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">m</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{ <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "value": 25.0, <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "unit": "cm"<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">} </pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON object</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">id</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A unique single-line text field. You can add&nbsp;<a href="https://shopify.dev/docs/apps/build/custom-data/metafields/list-of-validation-options" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);">validations</a>&nbsp;for&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">min</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">max</code>, and&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">regex</code>.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">1234</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">json</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A JSON-serializable value. This can be an object, an array, a string, a number, a boolean, or a null value.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{ <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "ingredient": "flour", <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "amount": 0.3<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">} </pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON data</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">link</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A text and URL pairing that can be used to store link content.<br><font color="#ff0000" style="">Define in pimcore example:<b> This is my url title|https://test.com</b></font></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{ <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "text": "Learn more", <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "url": "<a href="https://shopify.com/" target="_blank" rel="noreferrer external" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250); position: relative; margin-right: 20px;">https://shopify.com</a>"<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">} </pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON data</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">money</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A numeric amount, with a currency code that matches the store\'s currency.<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"><br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">You can&nbsp;<a rel="noreferrer external" target="_blank" href="https://help.shopify.com/manual/markets/languages/translate-adapt-app#create-custom-content-for-a-market" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250); position: relative; margin-right: 20px;">localize money metafields to a market</a>, but you can\'t translate them to a different language or locale.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "amount": "5.99", <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "currency_code": "CAD"<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">}</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON object</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><a id="multiLineText" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">multi_line_text_field</code></a></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A multi-line text field.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">Ingredients<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">Flour<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">Water<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">Milk<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">Eggs</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">number_decimal</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A number with decimal places in the range of +/-9999999999999.999999999.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">10.4</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><a id="numberInteger" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">number_integer
</code></a></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A whole number in the range of +/-9,007,199,254,740,991.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">10</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">integer</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><a id="rating" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">rating</code></a></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A rating measured on a specified scale.&nbsp;<a href="https://shopify.dev/docs/apps/build/custom-data/metafields/list-of-validation-options" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);">Validations</a>&nbsp;are required for ratings and support&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">min</code>&nbsp;and&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">max</code>.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{ <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "value": "3.5", <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "scale_min": "1.0", <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "scale_max": "5.0"<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">} </pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON object</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">rich_text_field </code><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background-image: none; background-position: 0% 0%; background-size: auto; background-repeat: repeat; background-attachment: scroll; background-origin: padding-box; background-clip: border-box; border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word; background-color: rgb(255, 0, 0);">(UNSUPPORTED!)</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><p style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; margin: 0px 0px 1em; padding: 0px; line-height: 1.5; font-size: inherit;">A rich text field supporting headings, lists, links, bold, and italics.</p><p style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; margin: 0px; padding: 0px; line-height: 1.5; font-size: inherit;">Learn more about&nbsp;<a href="https://shopify.dev/docs/apps/build/custom-data/metafields/list-of-data-types#rich-text-formatting" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; text-decoration-skip-ink: auto; border-bottom: 1px solid rgb(181, 201, 250);">rich text formatting</a>.</p></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "type": "root",<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "children": [<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">  {<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">   "type": "paragraph",<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">   "children": [<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">    {<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">     "type": "text",<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">     "value": "Bold text.",<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">     "bold": true<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">    }<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">   ]<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">  }<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> ]<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">}</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON object</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><a id="singleLineText" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250);"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">single_line_text_field</code></a></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A single-line text field.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">VIP shipping method</pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">url</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A URL with one of the allowed schemes:&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">https</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">http</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">mailto</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">sms</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">tel</code>.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;"><a href="https://www.shopify.com/" target="_blank" rel="noreferrer external" style="box-sizing: border-box; transition: border-color 0.2s linear; color: rgb(181, 201, 250); touch-action: manipulation; text-decoration-line: none; cursor: pointer; border-bottom: 1px solid rgb(181, 201, 250); position: relative; margin-right: 20px;">https://www.shopify.com</a></pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">string</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">yes</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">volume</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A value and a unit of volume. Valid unit values:&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">ml</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">cl</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">l</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">m3</code>&nbsp;(cubic meters),&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">us_fl_oz</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">us_pt</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">us_qt</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">us_gal</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">imp_fl_oz</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">imp_pt</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">imp_qt</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">imp_gal</code>.</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{ <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "value": 20.0, <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "unit": "ml"<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">} </pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON object</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr><tr style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; border-top: 1px solid rgb(36, 57, 61);"><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">weight</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">A value and a unit of weight. Valid unit values:&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">oz</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">lb</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">g</code>,&nbsp;<code class="text-highlight text-highlight--grey" style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; white-space-collapse: preserve; margin: 0px; overflow-x: auto; color: rgb(255, 255, 255); padding: 0px 4px; border-radius: 4px; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(9, 31, 33); border-color: rgb(36, 57, 61); border-style: solid; border-width: 1px; border-image: none 100% / 1 / 0 stretch; overflow-wrap: break-word;">kg</code></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;"><pre style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; font-family: &quot;JetBrains Mono&quot;, Monaco, Consolas, &quot;Lucida Console&quot;, monospace; font-variant-ligatures: none; text-wrap-mode: wrap; margin-top: 0px; margin-bottom: 0px; overflow-x: auto;">{ <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "value": 2.5, <br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;"> "unit": "kg"<br style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear;">} </pre></td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">JSON object</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td><td style="box-sizing: border-box; transition: background-color 0.2s linear, border-color 0.2s linear; padding: 12px; vertical-align: top; counter-reset: chapter 0;">no</td></tr></tbody></table>',
             'renderingClass' => '',
             'renderingData' => '',
             'border' => true,
          )),
          1 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'classificationStoreIdent',
             'title' => 'Classification Store Ident',
             'tooltip' => '',
             'mandatory' => false,
             'noteditable' => true,
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
          2 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'apiId',
             'title' => 'Api Id',
             'tooltip' => '',
             'mandatory' => false,
             'noteditable' => true,
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
          3 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'name',
             'title' => 'Name',
             'tooltip' => '',
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
          4 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'metaKey',
             'title' => 'Key',
             'tooltip' => '',
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
          5 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'metaType',
             'title' => 'type',
             'tooltip' => '',
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
             'defaultValue' => 'NUMBER_INTEGER',
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => '',
             'optionsProviderType' => 'class',
             'optionsProviderClass' => '\\App\\Pimcore\\Model\\OptionProviders\\MetafieldDefinition\\MetafieldDefinitionMetaTypeProvider',
             'optionsProviderData' => '',
          )),
          6 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Input::__set_state(array(
             'name' => 'namespace',
             'title' => 'Namespace',
             'tooltip' => '',
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
             'defaultValue' => 'custom',
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
          7 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Checkbox::__set_state(array(
             'name' => 'isPinned',
             'title' => 'Is Pinned',
             'tooltip' => '',
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
             'defaultValue' => 0,
             'defaultValueGenerator' => '',
          )),
          8 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Textarea::__set_state(array(
             'name' => 'description',
             'title' => 'Description',
             'tooltip' => '',
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
             'maxLength' => NULL,
             'showCharCount' => false,
             'excludeFromSearchIndex' => false,
             'height' => '',
             'width' => '',
          )),
          9 => 
          \Pimcore\Model\DataObject\ClassDefinition\Layout\Panel::__set_state(array(
             'name' => 'Access',
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
              \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'name' => 'adminAccess',
                 'title' => 'Admin Access',
                 'tooltip' => '',
                 'mandatory' => true,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'fieldtype' => '',
                 'relationType' => false,
                 'invisible' => true,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'MERCHANT_READ',
                    'value' => 'MERCHANT_READ',
                  ),
                  1 => 
                  array (
                    'key' => 'MERCHANT_READ_WRITE',
                    'value' => 'MERCHANT_READ_WRITE',
                  ),
                ),
                 'defaultValue' => 'MERCHANT_READ',
                 'columnLength' => 190,
                 'dynamicOptions' => false,
                 'defaultValueGenerator' => '',
                 'width' => '',
                 'optionsProviderType' => 'configure',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
              )),
              1 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'name' => 'customerAccountAccess',
                 'title' => 'Customer Account Access',
                 'tooltip' => '',
                 'mandatory' => true,
                 'noteditable' => false,
                 'index' => false,
                 'locked' => false,
                 'style' => '',
                 'permissions' => NULL,
                 'fieldtype' => '',
                 'relationType' => false,
                 'invisible' => true,
                 'visibleGridView' => false,
                 'visibleSearch' => false,
                 'blockedVarsForExport' => 
                array (
                ),
                 'options' => 
                array (
                  0 => 
                  array (
                    'key' => 'NONE',
                    'value' => 'NONE',
                  ),
                  1 => 
                  array (
                    'key' => 'READ',
                    'value' => 'READ',
                  ),
                  2 => 
                  array (
                    'key' => 'READ_WRITE',
                    'value' => 'READ_WRITE',
                  ),
                ),
                 'defaultValue' => 'NONE',
                 'columnLength' => 190,
                 'dynamicOptions' => false,
                 'defaultValueGenerator' => '',
                 'width' => '',
                 'optionsProviderType' => 'configure',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
              )),
              2 => 
              \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
                 'name' => 'storefrontAccess',
                 'title' => 'Storefront Access',
                 'tooltip' => '',
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
                    'key' => 'NONE',
                    'value' => 'NONE',
                  ),
                  1 => 
                  array (
                    'key' => 'PUBLIC_READ',
                    'value' => 'PUBLIC_READ',
                  ),
                ),
                 'defaultValue' => 'PUBLIC_READ',
                 'columnLength' => 190,
                 'dynamicOptions' => false,
                 'defaultValueGenerator' => '',
                 'width' => '',
                 'optionsProviderType' => 'configure',
                 'optionsProviderClass' => '',
                 'optionsProviderData' => '',
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
          10 => 
          \Pimcore\Model\DataObject\ClassDefinition\Data\Select::__set_state(array(
             'name' => 'ownerType',
             'title' => 'Owner Type',
             'tooltip' => '',
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
             'defaultValue' => 'PRODUCT',
             'columnLength' => 190,
             'dynamicOptions' => false,
             'defaultValueGenerator' => '',
             'width' => '',
             'optionsProviderType' => 'class',
             'optionsProviderClass' => '\\App\\Pimcore\\Model\\OptionProviders\\MetafieldDefinition\\MetafieldDefinitionOwnerTypeProvider',
             'optionsProviderData' => '',
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
   'icon' => '',
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
