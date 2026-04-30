/**
 * Icon-aware Select tag patch for Pimcore admin.
 *
 * Targets only `opendxp.object.tags.select` whose fieldConfig.optionsProviderClass
 * resolves to App\OpenDxp\Model\OptionProviders\IconOptionsProvider. Avoids the
 * fragile global Ext.form.field.ComboBox prototype patch that fights Pimcore-set
 * tpl/displayTpl and breaks unrelated combos (quicksearch et al).
 *
 * SVGs are served same-origin via /admin/icon-svg/{prefix}/{name}.
 */
(function () {
    'use strict';

    if (typeof opendxp === 'undefined' || !opendxp.object || !opendxp.object.tags || !opendxp.object.tags.select) {
        if (window.console) console.warn('[icon-combo] opendxp.object.tags.select not available, skipping patch');
        return;
    }

    var ICON_PROVIDER_NEEDLE = 'IconOptionsProvider';
    var ICON_PATTERN = /^[a-z]+:[a-z0-9-]+$/;

    function svgUrl(value) {
        var parts = String(value).split(':');
        return '/admin/icon-svg/' + parts[0] + '/' + parts[1];
    }

    function buildItemTpl() {
        return Ext.create('Ext.XTemplate',
            '<ul class="x-list-plain"><tpl for=".">',
            '<li role="option" class="x-boundlist-item" style="display:flex;align-items:center;gap:8px;padding:4px 8px;">',
            '<tpl if="this.isIcon(values.value)">',
            '  <img src="{[this.iconUrl(values.value)]}" width="20" height="20" style="flex:none;" alt="" />',
            '</tpl>',
            '<span style="flex:1;">{key}</span>',
            '<tpl if="this.isIcon(values.value)">',
            '  <span style="color:#888;font-size:11px;">{value}</span>',
            '</tpl>',
            '</li>',
            '</tpl></ul>',
            {
                isIcon: function (v) { return ICON_PATTERN.test(String(v || '')); },
                iconUrl: function (v) { var p = String(v).split(':'); return '/admin/icon-svg/' + p[0] + '/' + p[1]; }
            }
        );
    }

    function buildDisplayTpl() {
        // displayTpl is rendered into a <input> as text - HTML is stripped by ExtJS.
        // We keep it text-only here and overlay an <img> separately via DOM hook.
        return Ext.create('Ext.XTemplate',
            '<tpl for=".">',
            '{[Ext.util.Format.stripTags(values.key)]}',
            '</tpl>'
        );
    }

    function syncInputIconPreview(combo) {
        if (!combo.bodyEl || !combo.bodyEl.dom) return;
        var wrap = combo.bodyEl.dom;
        var preview = wrap.querySelector(':scope > img.x-icon-preview');
        var v = combo.getValue();
        if (!v || !ICON_PATTERN.test(v)) {
            if (preview) preview.remove();
            if (combo.inputEl && combo.inputEl.dom) {
                combo.inputEl.dom.style.paddingLeft = '';
            }
            return;
        }
        if (!preview) {
            preview = document.createElement('img');
            preview.className = 'x-icon-preview';
            preview.width = 16;
            preview.height = 16;
            preview.alt = '';
            preview.style.cssText = 'position:absolute;left:6px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:5;';
            wrap.style.position = 'relative';
            wrap.appendChild(preview);
            if (combo.inputEl && combo.inputEl.dom) {
                combo.inputEl.dom.style.paddingLeft = '26px';
            }
        }
        preview.src = svgUrl(v);
    }

    var origGetLayoutEdit = opendxp.object.tags.select.prototype.getLayoutEdit;

    opendxp.object.tags.select.prototype.getLayoutEdit = function () {
        var component = origGetLayoutEdit.apply(this, arguments);

        var providerClass = this.fieldConfig && this.fieldConfig.optionsProviderClass;
        if (!providerClass || providerClass.indexOf(ICON_PROVIDER_NEEDLE) < 0) {
            return component;
        }

        try {
            // Replace dropdown template with one that injects an <img> per option.
            component.tpl = buildItemTpl();
            component.displayTpl = buildDisplayTpl();

            component.on('afterrender', function () {
                syncInputIconPreview(component);
            });
            component.on('change', function () { syncInputIconPreview(component); });
            component.on('select', function () { syncInputIconPreview(component); });
            component.on('expand', function () {
                if (component.picker) {
                    component.picker.tpl = component.tpl;
                    if (typeof component.picker.refresh === 'function') {
                        component.picker.refresh();
                    }
                }
            });
        } catch (e) {
            if (window.console) console.warn('[icon-combo] patch failed', e);
        }

        return component;
    };

    if (window.console) console.log('[icon-combo] select tag patched');
})();
