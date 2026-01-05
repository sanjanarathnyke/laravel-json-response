<?php

namespace JsonBox\LaravelApiResponse;

use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // Bind the ApiResponse class into the container
        $this->app->singleton('api-response', function () {
            return new ApiResponse();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Nothing to boot for now
        // In future, we can publish config files here
    }
}
