<?php

namespace App\Providers;

use App\Services\SettingService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(SettingService::class, fn () => new SettingService());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Sobreescribir app.name con el nombre del centro configurado en BD
        try {
            $name = app(SettingService::class)->get('center_name');
            if ($name) {
                config(['app.name' => $name]);
            }
        } catch (\Throwable) {
            // Tabla settings aún no existe (ej: primer migrate)
        }
    }
}
