(function () {
    const cfg = window.BrowserGuardConfig || {};
    const state = { devtoolsHandled: false };

    function normalizeKey(value) {
        return String(value || '').trim().toUpperCase();
    }

    function shouldBlockShortcut(event, rule) {
        const ruleKey = normalizeKey(rule.key);
        const eventKey = normalizeKey(event.key);

        const ctrlOk = Boolean(rule.ctrl) === Boolean(event.ctrlKey);
        const shiftOk = Boolean(rule.shift) === Boolean(event.shiftKey);
        const altOk = Boolean(rule.alt) === Boolean(event.altKey);
        const metaOk = Boolean(rule.meta) === Boolean(event.metaKey);

        return ctrlOk && shiftOk && altOk && metaOk && ruleKey === eventKey;
    }

    function notify(message) {
        if (!cfg.showAlert) {
            return;
        }

        window.alert(message || cfg.alertMessage || 'This action is disabled on this page.');
    }

    function handleDevtools() {
        if (state.devtoolsHandled) {
            return;
        }

        state.devtoolsHandled = true;

        const action = String(cfg.devtoolsAction || 'alert').toLowerCase();
        const message = cfg.devtoolsMessage || 'Developer tools detected. This page is protected.';

        if (action === 'redirect') {
            notify(message);
            window.location.href = cfg.devtoolsRedirectUrl || '/';
            return;
        }

        if (action === 'blank') {
            document.documentElement.innerHTML = '<head><title>Protected</title></head><body style="display:flex;align-items:center;justify-content:center;min-height:100vh;font-family:Arial,sans-serif;padding:24px;text-align:center;">' +
                '<div><h2>Protected page</h2><p>' + message.replace(/</g, '&lt;').replace(/>/g, '&gt;') + '</p></div>' +
                '</body>';
            return;
        }

        notify(message);
    }

    if (cfg.blockRightClick) {
        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            notify(cfg.alertMessage);
        });
    }

    if (cfg.blockShortcuts) {
        document.addEventListener('keydown', function (event) {
            const shortcuts = Array.isArray(cfg.shortcuts) ? cfg.shortcuts : [];

            for (let i = 0; i < shortcuts.length; i += 1) {
                if (shouldBlockShortcut(event, shortcuts[i])) {
                    event.preventDefault();
                    event.stopPropagation();
                    notify(cfg.alertMessage);
                    return false;
                }
            }

            return true;
        }, true);
    }

    if (cfg.detectDevtools) {
        const threshold = Number(cfg.devtoolsThreshold || 160);
        const interval = Number(cfg.devtoolsCheckInterval || 1000);

        setInterval(function () {
            const widthGap = window.outerWidth - window.innerWidth;
            const heightGap = window.outerHeight - window.innerHeight;

            if (widthGap > threshold || heightGap > threshold) {
                handleDevtools();
            }
        }, interval);
    }
})();
