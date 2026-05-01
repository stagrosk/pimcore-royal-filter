/**
 * Hide price / compareAtPrice / purchasePrice fields inside the `Prices`
 * fieldcollection when the parent Product has productType === 'productsSet'.
 *
 * Scoped per-object (multiple product editors can be open simultaneously):
 * productType select tags and Prices fieldcollection tags are registered
 * against `tag.object.id`, so a change in one editor never affects another.
 */
(function () {
    'use strict';

    if (typeof opendxp === 'undefined' || !opendxp.object || !opendxp.object.tags || !opendxp.object.tags.fieldcollections) {
        return;
    }

    var TARGET_FIELDCOLLECTION = 'Prices';
    var HIDDEN_FIELDS = ['price', 'compareAtPrice', 'purchasePrice'];
    var PRODUCT_SET_TYPE = 'productsSet';

    // objectId -> { productTypeComponent, pricesTags: [tag, ...] }
    var registry = {};

    function entryFor(objectId) {
        if (!objectId) return null;
        if (!registry[objectId]) {
            registry[objectId] = { productTypeComponent: null, pricesTags: [] };
        }
        return registry[objectId];
    }

    function currentProductType(entry) {
        var c = entry && entry.productTypeComponent;
        if (c && !c.destroyed && typeof c.getValue === 'function') {
            return c.getValue();
        }
        return null;
    }

    function applyVisibility(fcTag, hidden) {
        if (!fcTag.currentElements) return;
        fcTag.currentElements.forEach(function (block) {
            if (!block || !block.fields) return;
            HIDDEN_FIELDS.forEach(function (fieldName) {
                var f = block.fields[fieldName];
                if (!f || !f.component) return;
                if (typeof f.component.setVisible === 'function') {
                    f.component.setVisible(!hidden);
                }
            });
        });
    }

    function refreshEntry(entry) {
        if (!entry) return;
        var hide = currentProductType(entry) === PRODUCT_SET_TYPE;
        // Drop destroyed tags lazily so the registry doesn't grow forever.
        entry.pricesTags = entry.pricesTags.filter(function (t) { return t && !t._destroyed; });
        entry.pricesTags.forEach(function (t) { applyVisibility(t, hide); });
    }

    var origAddBlock = opendxp.object.tags.fieldcollections.prototype.addBlockElement;
    opendxp.object.tags.fieldcollections.prototype.addBlockElement = function () {
        var ret = origAddBlock.apply(this, arguments);
        if (this.fieldConfig && this.fieldConfig.name === TARGET_FIELDCOLLECTION) {
            var entry = entryFor(this.object && this.object.id);
            applyVisibility(this, currentProductType(entry) === PRODUCT_SET_TYPE);
        }
        return ret;
    };

    var origSetObject = opendxp.object.tags.fieldcollections.prototype.setObject;
    opendxp.object.tags.fieldcollections.prototype.setObject = function (object) {
        origSetObject.apply(this, arguments);
        if (this.fieldConfig && this.fieldConfig.name === TARGET_FIELDCOLLECTION) {
            var entry = entryFor(this.object && this.object.id);
            if (entry && entry.pricesTags.indexOf(this) < 0) {
                entry.pricesTags.push(this);
            }
        }
    };

    if (opendxp.object.tags.select) {
        var origSelectGetLayoutEdit = opendxp.object.tags.select.prototype.getLayoutEdit;
        opendxp.object.tags.select.prototype.getLayoutEdit = function () {
            var component = origSelectGetLayoutEdit.apply(this, arguments);

            if (this.fieldConfig && this.fieldConfig.name === 'productType') {
                var self = this;
                var resolveEntry = function () { return entryFor(self.object && self.object.id); };

                component.on('afterrender', function () {
                    var entry = resolveEntry();
                    if (entry) entry.productTypeComponent = component;
                    refreshEntry(entry);
                });
                component.on('change', function () { refreshEntry(resolveEntry()); });
                component.on('destroy', function () {
                    var entry = resolveEntry();
                    if (entry && entry.productTypeComponent === component) {
                        entry.productTypeComponent = null;
                    }
                });
            }

            return component;
        };
    }
})();
