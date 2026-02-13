<?php

namespace App\Providers;

use App\Repositories\Contracts\VenueRepositoryInterface;
use App\Repositories\VenueRepository;
use Illuminate\Support\ServiceProvider;

/**
 * Application service provider.
 * Registers repository bindings (VenueRepositoryInterface -> VenueRepository).
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(VenueRepositoryInterface::class, VenueRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
