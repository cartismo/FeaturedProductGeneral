<?php

namespace Modules\FeaturedProductGeneral\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        $this->mapAdminRoutes();
    }

    protected function mapAdminRoutes(): void
    {
        Route::middleware([
            'web',
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])
            ->prefix('admin')
            ->group(module_path('FeaturedProductGeneral', '/Routes/admin.php'));
    }
}