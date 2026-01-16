<?php

namespace Modules\FeaturedProductGeneral\Providers;

use Illuminate\Support\ServiceProvider;

class FeaturedProductGeneralServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'FeaturedProductGeneral';
    protected string $moduleNameLower = 'featuredproductgeneral';

    public function boot(): void
    {
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');

        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }
}