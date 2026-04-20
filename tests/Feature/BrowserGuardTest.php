<?php

use Illuminate\Support\Facades\Route;

it('loads the browser guard configuration', function () {
    expect(config('browser-guard.enabled'))->toBeTrue();
});

it('renders the browser guard script directive if in global mode', function () {
    // Register a simple route that uses the directive
    Route::get('/test-directive', function () {
        return \Illuminate\Support\Facades\Blade::render('<html><body>@browserGuardScripts</body></html>');
    });

    // In global mode, the directive should render the script
    config()->set('browser-guard.mode', 'global');

    $response = $this->get('/test-directive');
    
    $response->assertOk();
    $content = $response->getContent();
    expect($content)->toContain('vendor/browser-guard/browser-guard.js');
});

it('does not render the script if disabled', function () {
    Route::get('/test-directive-disabled', function () {
        return \Illuminate\Support\Facades\Blade::render('<html><body>@browserGuardScripts</body></html>');
    });

    config()->set('browser-guard.enabled', false);

    $response = $this->get('/test-directive-disabled');
    
    $response->assertOk();
    expect($response->getContent())->not->toContain('vendor/browser-guard/browser-guard.js');
});
