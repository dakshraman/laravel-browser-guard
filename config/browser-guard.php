<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Master switch
    |--------------------------------------------------------------------------
    */
    'enabled' => env('BROWSER_GUARD_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    | global     => script is rendered anywhere @browserGuardScripts is used
    | middleware => script renders only for routes using the browser.guard middleware
    */
    'mode' => env('BROWSER_GUARD_MODE', 'global'),

    /*
    |--------------------------------------------------------------------------
    | Excluded paths
    |--------------------------------------------------------------------------
    | Supports request()->is() style patterns.
    */
    'except_paths' => [
        'admin/api/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Restrictions
    |--------------------------------------------------------------------------
    */
    'block_right_click' => true,
    'block_shortcuts' => true,
    'detect_devtools' => true,

    /*
    |--------------------------------------------------------------------------
    | Shortcut map
    |--------------------------------------------------------------------------
    | Examples:
    |   ['key' => 'F12']
    |   ['ctrl' => true, 'shift' => true, 'key' => 'I']
    */
    'shortcuts' => [
        ['key' => 'F12'],
        ['ctrl' => true, 'shift' => true, 'key' => 'I'],
        ['ctrl' => true, 'shift' => true, 'key' => 'J'],
        ['ctrl' => true, 'shift' => true, 'key' => 'C'],
        ['ctrl' => true, 'key' => 'U'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert UI
    |--------------------------------------------------------------------------
    */
    'show_alert' => true,
    'alert_message' => 'This action is disabled on this page.',

    /*
    |--------------------------------------------------------------------------
    | DevTools handling
    |--------------------------------------------------------------------------
    | action: alert | redirect | blank
    */
    'devtools_action' => 'alert',
    'devtools_message' => 'Developer tools detected. This page is protected.',
    'devtools_redirect_url' => '/',
    'devtools_check_interval' => 1000,
    'devtools_threshold' => 160,

    /*
    |--------------------------------------------------------------------------
    | Middleware headers
    |--------------------------------------------------------------------------
    */
    'no_cache_headers' => true,
];
