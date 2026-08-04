<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        $this->forceHttps();
    }

    /**
     * Paksa skema HTTPS untuk semua URL yang digenerate oleh Laravel.
     *
     * Railway meneruskan permintaan melalui reverse proxy, sehingga protokol
     * asli dapat diketahui dari header X-Forwarded-Proto. Skema juga dipaksa
     * pada environment production, kecuali saat pengembangan di localhost.
     */
    protected function forceHttps(): void
    {
        $isHttpsProxy = request()->header('x-forwarded-proto') === 'https';
        $appUrl = (string) config('app.url');
        $isLocalDev = str_contains($appUrl, '//localhost')
            || str_contains($appUrl, '127.0.0.1');

        if ($isHttpsProxy || (config('app.env') === 'production' && !$isLocalDev)) {
            URL::forceScheme('https');
        }
    }
}
