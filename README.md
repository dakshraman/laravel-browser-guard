# Browser Guard

A Laravel package that discourages:

- right click / context menu
- selected shortcut keys like `F12`, `Ctrl+Shift+I`, `Ctrl+Shift+J`, `Ctrl+Shift+C`, `Ctrl+U`
- casual DevTools opening through simple browser-side detection

## Important note

This package does **not** provide real security. Anything running in the browser can be bypassed by a determined user. Use it only as a UI deterrent, not as a protection layer for sensitive data.

## Installation

```bash
composer require dakshraman/browser-guard
php artisan browser-guard:install
```

Or publish manually:

```bash
php artisan vendor:publish --tag=browser-guard-config
php artisan vendor:publish --tag=browser-guard-assets
```

## Add the script to your layout

In your main Blade layout, just before `</body>`:

```blade
@browserGuardScripts
```

## Usage modes

### 1. Global mode

In `config/browser-guard.php`:

```php
'mode' => 'global',
```

The script will load wherever `@browserGuardScripts` exists.

### 2. Middleware mode

In `config/browser-guard.php`:

```php
'mode' => 'middleware',
```

Then apply the middleware only to routes you want to protect:

```php
Route::middleware('browser.guard')->group(function () {
    Route::get('/protected-page', function () {
        return view('welcome');
    });
});
```

## Useful config

```php
'enabled' => true,
'block_right_click' => true,
'block_shortcuts' => true,
'detect_devtools' => true,
'show_alert' => true,
'alert_message' => 'This action is disabled on this page.',
'devtools_action' => 'alert', // alert | redirect | blank
'devtools_redirect_url' => '/',
```

## Custom shortcuts

```php
'shortcuts' => [
    ['key' => 'F12'],
    ['ctrl' => true, 'shift' => true, 'key' => 'I'],
    ['ctrl' => true, 'shift' => true, 'key' => 'J'],
    ['ctrl' => true, 'shift' => true, 'key' => 'C'],
    ['ctrl' => true, 'key' => 'U'],
],
```

## Excluding paths

```php
'except_paths' => [
    'admin/api/*',
    'health-check',
],
```

## File structure

```text
browser-guard/
├── composer.json
├── config/
│   └── browser-guard.php
├── resources/
│   ├── js/
│   │   └── browser-guard.js
│   └── views/
│       └── script.blade.php
└── src/
    ├── BrowserGuardServiceProvider.php
    ├── Commands/
    │   └── InstallBrowserGuardCommand.php
    └── Middleware/
        └── BrowserGuardMiddleware.php
```

## Suggested improvements

If you want this package to feel more production-ready, the next upgrades are:

- add tests with Orchestra Testbench
- add a facade or helper for runtime toggling
- add a small toast UI instead of `alert()`
- add IP/user-based exemptions
- add an inline-script fallback when published assets are missing

## License

MIT
