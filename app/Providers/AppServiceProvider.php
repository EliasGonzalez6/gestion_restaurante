<?php

namespace App\Providers;

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
        // Cambiar el directorio público a public_html
        if(env('APP_PUBLIC_PATH') === 'public_html') {
            $this->app->bind('path.public', function() {
                return base_path('public_html');
            });
        }
    }
}
