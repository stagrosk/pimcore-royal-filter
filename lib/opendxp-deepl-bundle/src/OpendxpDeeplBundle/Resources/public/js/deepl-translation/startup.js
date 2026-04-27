opendxp.registerNS("opendxp.plugin.deeplTranslate");

opendxp.plugin.deeplTranslate = Class.create({
    initialize: function () {
        document.addEventListener(opendxp.events.postOpenDocument, this.postOpenDocument.bind(this));
    },

    createTranslation: function (document) {
        var filteredWebsiteLanguages = opendxp.settings.websiteLanguages.filter(function(value, index, arr) {
            return value !== document.data.properties.language.data;
        }); // Do not display the language of the current document

        var pageForm = new Ext.form.FormPanel({
            border: false,
            defaults: {
                labelWidth: 170
            },
            items: [{
                xtype: "combo",
                name: "language",
                store: filteredWebsiteLanguages,
                editable: false,
                triggerAction: 'all',
                mode: "local",
                fieldLabel: t('language'),
                listeners: {
                    select: function (el) {
                        if (el.getValue() === 'de_DE') {
                            pageForm.getComponent("parent").setValue('/de/articles');
                        } else {
                            pageForm.getComponent("parent").setValue('/'+el.getValue()+'/articles');
                        }
                    }.bind(this)
                }
            }, {
                xtype: "textfield",
                name: "parent",
                itemId: "parent",
                width: "100%",
                fieldCls: "input_drop_target",
                fieldLabel: t("parent"),
                listeners: {
                    "render": function (el) {
                        new Ext.dd.DropZone(el.getEl(), {
                            reference: this,
                            ddGroup: "element",
                            getTargetFromEvent: function (e) {
                                return this.getEl();
                            }.bind(el),

                            onNodeOver: function (target, dd, e, data) {
                                // Allow only documents to be set as parent
                                if (data.records.length === 1 && data.records[0].data.elementType === "document") {
                                    return Ext.dd.DropZone.prototype.dropAllowed;
                                }
                            },

                            onNodeDrop: function (target, dd, e, data) {
                                if (!opendxp.helpers.dragAndDropValidateSingleItem(data)) {
                                    return false;
                                }

                                data = data.records[0].data;
                                if (data.elementType === "document") {
                                    this.setValue(data.path);
                                    return true;
                                }
                                return false;
                            }.bind(el)
                        });
                    }
                }
            }]
        });

        var win = new Ext.Window({
            width: 600,
            bodyStyle: "padding:10px",
            items: [pageForm],
            buttons: [{
                text: t("cancel"),
                iconCls: "opendxp_icon_cancel",
                handler: function () {
                    win.close();
                }
            }, {
                text: t("apply"),
                iconCls: "opendxp_icon_apply",
                handler: function () {
                    let params = pageForm.getForm().getFieldValues();
                    let id = document.data.id;
                    win.disable();
                    win.setTitle("Saving document...");

                    var doTranslate = function () {
                        win.setTitle("Translating...");
                        Ext.Ajax.request({
                            url: '/admin/deeplTranslateDocument',
                            method: "post",
                            params: {
                                language: params.language,
                                id: id,
                                parent: params.parent
                            },
                            success: function (response) {
                                var res = Ext.decode(response.responseText);
                                if (res.success) {
                                    win.close();
                                    Ext.MessageBox.alert(t('Success'), 'Successfully translated document to "' + params.parent + '" named "' + res.key + '"');
                                    opendxp.helpers.openDocument(res.id, "page");
                                } else {
                                    Ext.MessageBox.alert(t('Error'), res.message);
                                    win.enable();
                                    win.setTitle("");
                                }
                            },
                            failure: function () {
                                Ext.MessageBox.alert(t('Error'), 'Translation request failed. Please try again.');
                                win.enable();
                                win.setTitle("");
                            }
                        });
                    };

                    var translated = false;
                    var onSaved = function () {
                        if (!translated) {
                            translated = true;
                            doTranslate();
                        }
                    };

                    try {
                        document.save("version", null, onSaved);
                        // fallback if save silently skips (e.g. no changes)
                        setTimeout(onSaved, 3000);
                    } catch (e) {
                        onSaved();
                    }
                }.bind(this)
            }]
        });

        win.show();
    },

    postOpenDocument: function (e) {
        if (e.detail.type !== 'page') {
            return;
        }

        let menuParent = e.detail.document.toolbar.items.items

        // Check if Translation button exists and append to it
        menuParent.forEach(function(menu){
            if (menu.config.iconCls === 'opendxp_material_icon_translation opendxp_material_icon') {
                menu.btnInnerEl.component.menu.items.items[0].menu.add({
                    text: t('Deepl Translation'),
                    iconCls: 'opendxp_material_icon_translation',
                    scale: 'small',
                    handler: this.createTranslation.bind(this, e.detail.document),
                });
            }
        }.bind(this));
    }
});

const deeplTranslatePlugin = new opendxp.plugin.deeplTranslate();
