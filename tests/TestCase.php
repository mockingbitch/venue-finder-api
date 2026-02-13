<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for all application tests.
 * Uses CreatesApplication to bootstrap the Laravel application.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
