<?php

namespace Dakshraman\BrowserGuard\Commands;

use Illuminate\Console\Command;

class InstallBrowserGuardCommand extends Command
{
    protected $signature = 'browser-guard:install {--skip-config : Skip interactive configuration prompts}';

    protected $description = 'Install and configure Browser Guard';

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

        if (! $this->option('skip-config')) {
            $this->configureGuards();
        }

        $this->info('');
        $this->info('Browser Guard installed successfully.');
        $this->line('Add @browserGuardScripts before </body> in your layout.');
        $this->line('Use mode=middleware and apply browser.guard only where needed, if preferred.');

        return self::SUCCESS;
    }

    protected function configureGuards(): void
    {
        $this->info('');
        $this->info('Which guards would you like to enable?');
        $this->line('');

        $blockRightClick = $this->confirm('Block right-click / context menu?', true);
        $blockShortcuts = $this->confirm('Block keyboard shortcuts (F12, Ctrl+U, etc.)?', true);
        $detectDevtools = $this->confirm('Detect DevTools opening?', true);

        $this->info('');
        $this->line('How should Browser Guard run?');
        $this->line('  [global]   - Script loads on every page with @browserGuardScripts');
        $this->line('  [middleware]- Script loads only on routes using browser.guard middleware');
        $this->line('');

        $mode = $this->choice('Select mode', ['global', 'middleware'], 0);

        $showAlert = true;
        $alertMessage = 'This action is disabled on this page.';

        if ($blockRightClick || $blockShortcuts || $detectDevtools) {
            $showAlert = $this->confirm('Show alert when a guard is triggered?', true);
            if ($showAlert) {
                $alertMessage = $this->ask('Alert message', $alertMessage);
            }
        }

        $devtoolsAction = 'alert';
        if ($detectDevtools) {
            $this->line('');
            $this->line('What should happen when DevTools is detected?');
            $devtoolsAction = $this->choice('DevTools action', ['alert', 'redirect', 'blank'], 0);
        }

        $this->writeConfig([
            'block_right_click' => $blockRightClick,
            'block_shortcuts' => $blockShortcuts,
            'detect_devtools' => $detectDevtools,
            'mode' => $mode,
            'show_alert' => $showAlert,
            'alert_message' => $alertMessage,
            'devtools_action' => $devtoolsAction,
        ]);

        $this->info('');
        $this->info('Configuration saved to config/browser-guard.php');
    }

    protected function writeConfig(array $options): void
    {
        $configPath = config_path('browser-guard.php');

        if (! file_exists($configPath)) {
            return;
        }

        $lines = file($configPath);

        foreach ($lines as &$line) {
            if (str_starts_with(trim($line), "'block_right_click'")) {
                $line = "    'block_right_click' => " . var_export($options['block_right_click'], true) . ",\n";
            } elseif (str_starts_with(trim($line), "'block_shortcuts'")) {
                $line = "    'block_shortcuts' => " . var_export($options['block_shortcuts'], true) . ",\n";
            } elseif (str_starts_with(trim($line), "'detect_devtools'")) {
                $line = "    'detect_devtools' => " . var_export($options['detect_devtools'], true) . ",\n";
            } elseif (str_starts_with(trim($line), "'mode'")) {
                $line = "    'mode' => env('BROWSER_GUARD_MODE', '" . $options['mode'] . "'),\n";
            } elseif (str_starts_with(trim($line), "'show_alert'")) {
                $line = "    'show_alert' => " . var_export($options['show_alert'], true) . ",\n";
            } elseif (str_starts_with(trim($line), "'alert_message'")) {
                $line = "    'alert_message' => '" . addslashes($options['alert_message']) . "',\n";
            } elseif (str_starts_with(trim($line), "'devtools_action'")) {
                $line = "    'devtools_action' => '" . $options['devtools_action'] . "',\n";
            }
        }

        file_put_contents($configPath, implode('', $lines));
    }
}
