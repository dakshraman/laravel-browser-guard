@php
    $enabled = (bool) config('browser-guard.enabled', true);
    $mode = (string) config('browser-guard.mode', 'global');
    $middlewareEnabled = (bool) ($browserGuardEnabledFromMiddleware ?? false);
    $exceptPaths = (array) config('browser-guard.except_paths', []);

    $isExcluded = false;
    foreach ($exceptPaths as $pattern) {
        if (request()->is($pattern)) {
            $isExcluded = true;
            break;
        }
    }

    $shouldRender = $enabled
        && ! $isExcluded
        && ($mode !== 'middleware' || $middlewareEnabled);
@endphp

@if ($shouldRender)
    <script>
        window.BrowserGuardConfig = {!! json_encode([
            'blockRightClick' => (bool) config('browser-guard.block_right_click', true),
            'blockShortcuts' => (bool) config('browser-guard.block_shortcuts', true),
            'detectDevtools' => (bool) config('browser-guard.detect_devtools', true),
            'showAlert' => (bool) config('browser-guard.show_alert', true),
            'alertMessage' => (string) config('browser-guard.alert_message', 'This action is disabled on this page.'),
            'shortcuts' => array_values((array) config('browser-guard.shortcuts', [])),
            'devtoolsAction' => (string) config('browser-guard.devtools_action', 'alert'),
            'devtoolsMessage' => (string) config('browser-guard.devtools_message', 'Developer tools detected. This page is protected.'),
            'devtoolsRedirectUrl' => (string) config('browser-guard.devtools_redirect_url', '/'),
            'devtoolsCheckInterval' => (int) config('browser-guard.devtools_check_interval', 1000),
            'devtoolsThreshold' => (int) config('browser-guard.devtools_threshold', 160),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    </script>
    <script src="{{ asset('vendor/browser-guard/browser-guard.js') }}"></script>
@endif
