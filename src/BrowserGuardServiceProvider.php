<?php

namespace Dakshraman\BrowserGuard;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Dakshraman\BrowserGuard\Commands\InstallBrowserGuardCommand;
use Dakshraman\BrowserGuard\Middleware\BrowserGuardMiddleware;

class BrowserGuardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/browser-guard.php', 'browser-guard');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallBrowserGuardCommand::class,
            ]);
        }
    }

    public function boot(Router $router): void
    {
        $this->publishes([
            __DIR__ . '/../config/browser-guard.php' => config_path('browser-guard.php'),
        ], 'browser-guard-config');

        $this->publishes([
            __DIR__ . '/../resources/js/browser-guard.js' => public_path('vendor/browser-guard/browser-guard.js'),
        ], 'browser-guard-assets');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'browser-guard');

        Blade::directive('browserGuardScripts', function () {
            return "<?php echo view('browser-guard::script')->render(); ?>";
        });

        $router->aliasMiddleware('browser.guard', BrowserGuardMiddleware::class);
    }
}
