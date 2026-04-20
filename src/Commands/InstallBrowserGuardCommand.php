<?php

namespace Dakshraman\BrowserGuard\Commands;

use Illuminate\Console\Command;

class InstallBrowserGuardCommand extends Command
{
    protected $signature = 'browser-guard:install';

    protected $description = 'Publish Browser Guard configuration and assets';

    public function handle(): int
    {
        $this->call('vendor:publish', [
            '--tag' => 'browser-guard-config',
            '--force' => true,
        ]);

        $this->call('vendor:publish', [
            '--tag' => 'browser-guard-assets',
            '--force' => true,
        ]);

        $this->info('Browser Guard installed successfully.');
        $this->line('Add @browserGuardScripts before </body> in your layout.');
        $this->line('Use mode=middleware and apply browser.guard only where needed, if preferred.');

        return self::SUCCESS;
    }
}
