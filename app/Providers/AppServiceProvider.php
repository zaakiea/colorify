<?php

namespace App\Providers;

use App\Models\ColorPalette;
use App\Observers\ColorPaletteObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        ColorPalette::observe(ColorPaletteObserver::class);
    }
}
