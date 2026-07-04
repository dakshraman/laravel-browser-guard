# Browser Guard

A Laravel package that discourages:

- right click / context menu
- selected shortcut keys like `F12`, `Ctrl+Shift+I`, `Ctrl+Shift+J`, `Ctrl+Shift+C`, `Ctrl+U`
- casual DevTools opening through simple browser-side detection

## Important note

This package does **not** provide real security. Anything running in the browser can be bypassed by a determined user. Use it only as a UI deterrent, not as a protection layer for sensitive data.

## Requirements

- PHP 8.1+
- Laravel 10, 11, 12, or 13

## Installation

```bash
composer require dakshraman/laravel-browser-guard
php artisan browser-guard:install
```

The install command will publish config and assets, then walk you through configuration interactively:

```
Which guards would you like to enable?

 Block right-click / context menu? (yes/no) [yes]:
 > yes
 Block keyboard shortcuts (F12, Ctrl+U, etc.)? (yes/no) [yes]:
 > yes
 Detect DevTools opening? (yes/no) [yes]:
 > yes

How should Browser Guard run?
 [global]   - Script loads on every page with @browserGuardScripts
 [middleware]- Script loads only on routes using browser.guard middleware

 Select mode [global]:
 [0] global
 [1] middleware
 > 0

 Show alert when a guard is triggered? (yes/no) [yes]:
 > yes
 Alert message [This action is disabled on this page.]:
 >

 What should happen when DevTools is detected?
 DevTools action [alert]:
 [0] alert
 [1] redirect
 [2] blank
 > 0

Configuration saved to config/browser-guard.php
```

### Skip interactive prompts

For CI or scripted installs, use `--skip-config`:

```bash
php artisan browser-guard:install --skip-config
```

### Manual publish

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

```php
'mode' => 'global',
```

The script will load wherever `@browserGuardScripts` exists.

### 2. Middleware mode

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

The middleware also sets `no-cache` headers to prevent browser caching of protected pages.

## Configuration

```php
// Master switch
'enabled' => true,

// Mode: 'global' or 'middleware'
'mode' => 'global',

// Guards
'block_right_click' => true,
'block_shortcuts' => true,
'detect_devtools' => true,

// Alert UI
'show_alert' => true,
'alert_message' => 'This action is disabled on this page.',

// DevTools handling: 'alert' | 'redirect' | 'blank'
'devtools_action' => 'alert',
'devtools_message' => 'Developer tools detected. This page is protected.',
'devtools_redirect_url' => '/',
'devtools_check_interval' => 1000,
'devtools_threshold' => 160,
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

## Testing

```bash
composer test
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

## License

MIT
