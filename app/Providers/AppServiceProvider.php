<?php

namespace App\Providers;

use Carbon\Carbon;
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
    public function boot(): void
    {
        // Set locale (opsional, untuk nama bulan/hari)
        Carbon::setLocale('id');

        // Set format default untuk toFormattedDateString(), format(), dll.
        Carbon::macro('toAppFormat', function () {
            return $this->format('d/m/Y');
        });
    }
}
