(function () {
    var csData = null;

    function ensureCsData(callback) {
        if (csData) {
            callback();
            return;
        }
        Ext.Ajax.request({
            url: '/admin/classification-store/group-key-map',
            method: 'GET',
            success: function (response) {
                try {
                    csData = JSON.parse(response.responseText);
                } catch (e) {
                    console.error('[FilterConfig] Failed to parse group-key-map response', e);
                    return;
                }
                callback();
            },
            failure: function () {
                console.error('[FilterConfig] Failed to load group-key-map');
            }
        });
    }

    function getKeyIdsForGroup(groupId) {
        var ids = {};
        csData.relations.forEach(function (r) {
            if (String(r.groupId) === String(groupId)) {
                ids[String(r.keyId)] = true;
            }
        });
        return ids;
    }

    function getGroupIdsForKey(keyId) {
        var ids = {};
        csData.relations.forEach(function (r) {
            if (String(r.keyId) === String(keyId)) {
                ids[String(r.groupId)] = true;
            }
        });
        return ids;
    }

    function findSibling(combo, siblingName) {
        // Both combos share the same immediate parent panel in fieldcollection item
        var parent = combo.ownerCt;
        if (!parent) return null;
        // Search up to 3 levels to find sibling
        for (var i = 0; i < 3; i++) {
            var found = null;
            parent.query('combo').forEach(function (c) {
                if (c !== combo && c.getName && c.getName() === siblingName) {
                    found = c;
                }
            });
            if (found) return found;
            parent = parent.ownerCt;
            if (!parent) break;
        }
        return null;
    }

    function filterComboStore(combo, allowedIds) {
        var store = combo.getStore();
        if (!store) return;

        // Save original data once
        if (!combo._fcOriginalData) {
            combo._fcOriginalData = store.getRange().map(function (r) {
                return [r.get('value'), r.get('key')];
            });
        }

        if (!allowedIds) {
            store.loadData(combo._fcOriginalData);
            return;
        }

        var filtered = combo._fcOriginalData.filter(function (item) {
            return allowedIds[String(item[0])] === true;
        });
        store.loadData(filtered);
    }

    function wireUpItem(groupCombo, keyCombo) {
        if (groupCombo._fcWired) return;
        groupCombo._fcWired = true;
        keyCombo._fcWired = true;

        groupCombo.on('change', function (field, newValue) {
            if (newValue) {
                filterComboStore(keyCombo, getKeyIdsForGroup(newValue));
                // Clear key if not in allowed set
                var keyValue = keyCombo.getValue();
                if (keyValue && !getKeyIdsForGroup(newValue)[String(keyValue)]) {
                    keyCombo.setValue(null);
                }
            } else {
                filterComboStore(keyCombo, null);
            }
        });

        keyCombo.on('change', function (field, newValue) {
            if (newValue) {
                filterComboStore(groupCombo, getGroupIdsForKey(newValue));
            } else {
                filterComboStore(groupCombo, null);
            }
        });

        // Apply initial filter based on current values
        var groupVal = groupCombo.getValue();
        var keyVal = keyCombo.getValue();
        if (groupVal) {
            filterComboStore(keyCombo, getKeyIdsForGroup(groupVal));
        } else if (keyVal) {
            filterComboStore(groupCombo, getGroupIdsForKey(keyVal));
        }
    }

    // Hook into ComboBox afterRender
    var origAfterRender = Ext.form.field.ComboBox.prototype.afterRender;
    Ext.form.field.ComboBox.prototype.afterRender = function () {
        origAfterRender.apply(this, arguments);

        var combo = this;
        var name = combo.getName ? combo.getName() : '';

        if (name !== 'parameterGroup' && name !== 'parameterKey') return;

        ensureCsData(function () {
            var siblingName = name === 'parameterGroup' ? 'parameterKey' : 'parameterGroup';
            var sibling = findSibling(combo, siblingName);
            if (!sibling) return;

            var groupCombo = name === 'parameterGroup' ? combo : sibling;
            var keyCombo = name === 'parameterKey' ? combo : sibling;
            wireUpItem(groupCombo, keyCombo);
        });
    };
})();
