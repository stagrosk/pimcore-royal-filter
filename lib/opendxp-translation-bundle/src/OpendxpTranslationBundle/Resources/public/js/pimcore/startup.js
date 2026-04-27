opendxp.registerNS("opendxp.plugin.TranslationBundle");

opendxp.plugin.TranslationBundle = Class.create({
    initialize: function () {
        Ext.Ajax.request({
            url: "/admin/translate-provider",
            method: "GET",
            success: function (response) {
                var res = Ext.decode(response.responseText);
                opendxp.globalmanager.add('translationBundle_provider', res.provider);
            }
        });
    },
});

var TranslationBundlePlugin = new opendxp.plugin.TranslationBundle();
