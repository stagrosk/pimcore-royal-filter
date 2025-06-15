(function () {
    const origCreate = Ext.create;

    Ext.create = function () {
        const args = arguments;

        if (args[0] === 'Ext.window.Toast' && typeof args[1] === 'object') {
            const config = args[1];
            const userCloseAction = config.closeAction;
            const userCloseDelay = config.autoCloseDelay;

            config.autoClose = true;

            if (typeof userCloseDelay === 'undefined') {
                config.autoCloseDelay = 5000;
            }

            if (typeof userCloseAction === 'undefined') {
                config.closeAction = 'destroy';
            }
        }

        return origCreate.apply(this, arguments);
    };
})();
