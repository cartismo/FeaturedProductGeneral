<?php

use Illuminate\Support\Facades\Route;
use Modules\FeaturedProductGeneral\Http\Controllers\Admin\SettingsController;

Route::prefix('modules/general/featured-product-general')->name('admin.general.featured.')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/products', [SettingsController::class, 'addProduct'])->name('products.add');
    Route::delete('/products/{featuredProduct}', [SettingsController::class, 'removeProduct'])->name('products.remove');
    Route::post('/products/reorder', [SettingsController::class, 'reorder'])->name('products.reorder');
    Route::post('/products/{featuredProduct}/toggle', [SettingsController::class, 'toggle'])->name('products.toggle');
});