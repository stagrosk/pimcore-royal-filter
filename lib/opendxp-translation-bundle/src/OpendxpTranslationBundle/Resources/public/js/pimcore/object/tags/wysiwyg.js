/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Enterprise License (PEL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 * @license    http://www.pimcore.org/license     GPLv3 and PEL
 */

pimcore.registerNS("pimcore.object.tags.wysiwyg");
pimcore.object.tags.wysiwyg = Class.create(pimcore.object.tags.wysiwyg, {
    getLayoutEdit: function () {
        var width = '550';

        this.getLayout();
        this.component.on("afterlayout", this.startWysiwygEditor.bind(this));

        if(this.ddWysiwyg) {
            this.component.on("beforedestroy", function () {
                const beforeDestroyWysiwyg = new CustomEvent(pimcore.events.beforeDestroyWysiwyg, {
                    detail: {
                        context: "object",
                    },
                });
                document.dispatchEvent(beforeDestroyWysiwyg);
            }.bind(this));
        }

        if (this.fieldConfig.width) {
            width = this.fieldConfig.width;
        }

        if (this.context && this.context.language) {
            this.translateButton = new pimcore.object.elementservice.translateButton(
                this.object.data.general.id,
                this.fieldConfig.name,
                this,
                'wysiwyg',
                this.context.language
            );
        } else {
            this.translateButton = null;
        }

        var items = [this.component];
        if (this.translateButton) {
            items.push(this.translateButton);
        }

        return Ext.create('Ext.Panel', {
            layout: 'vbox',
            items: items,
            componentCls: "object_field custom_wysiwyg",
            border: false,
            width: width,
            style: {
                padding: 0,
                marginBottom: '10px',
            },
        });
    },
});
