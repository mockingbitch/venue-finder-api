<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

/**
 * Trait to create and bootstrap the Laravel application for testing.
 */
trait CreatesApplication
{
    /**
     * Create the application instance for testing.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
