// Override translation request to send current input text instead of reading from DB
function handleTranslationRequest(id, fieldName, component, type, lang, formality) {
    var text = '';

    switch (type) {
        case 'input':
            text = component.getRawValue();
            break;
        case 'textarea':
            text = component.component ? component.component.getValue() : component.getValue();
            break;
        case 'wysiwyg':
            var editor = tinymce.get(component.editableDivId);
            text = editor ? editor.getContent({format: 'text'}) : '';
            break;
    }

    if (!text || text.trim() === '') {
        pimcore.helpers.showNotification(t("info"), "Field is empty, nothing to translate.", "info");
        return;
    }

    Ext.Ajax.request({
        url: "/admin/object/translate-text",
        method: "POST",
        params: {
            text: text,
            lang: lang,
            formality: formality
        },
        success: function (response) {
            var res = Ext.decode(response.responseText);

            if (res.success) {
                switch (type) {
                    case 'wysiwyg':
                        tinymce.get(component.editableDivId).setContent(res.data);
                        break;
                    case 'input':
                        component.setRawValue(res.data);
                        break;
                    case 'textarea':
                        if (component.component) {
                            component.component.setValue(res.data);
                        } else {
                            component.setValue(res.data);
                        }
                        break;
                }
            } else {
                pimcore.helpers.showPrettyError('object', t("error"), t("saving_failed"), res.message);
            }
        }
    });
}
