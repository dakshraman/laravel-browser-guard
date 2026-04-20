<?php

namespace Dakshraman\BrowserGuard\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Dakshraman\BrowserGuard\BrowserGuardServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            BrowserGuardServiceProvider::class,
        ];
    }
}
