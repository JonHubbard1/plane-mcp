<?php

namespace Technoliga\PlaneMcp;

use Illuminate\Support\ServiceProvider;

class PlaneMcpServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/plane-mcp.php', 'plane-mcp'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/plane-mcp.php' => config_path('plane-mcp.php'),
            ], 'config');
        }
    }
}