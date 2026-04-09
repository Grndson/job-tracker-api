<?php

namespace App\Providers;

use App\Services\ApplicationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ApplicationService::class);
    }

    public function boot(): void
    {
        //
    }
}