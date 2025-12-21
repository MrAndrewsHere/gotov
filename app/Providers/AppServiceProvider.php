<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\CharityProjectService;
use App\Services\DonationService;
use App\Services\Interfaces\CharityProjectServiceInterface;
use App\Services\Interfaces\DonationServiceInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerServiceInterfaces();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::preventLazyLoading(! app()->isProduction());
        JsonResource::withoutWrapping();

        $this->configureCacheHeaders();
    }

    private function registerServiceInterfaces(): void
    {
        $this->app->bind(CharityProjectServiceInterface::class, CharityProjectService::class);
        $this->app->bind(DonationServiceInterface::class, DonationService::class);
    }

    private function configureCacheHeaders(): void
    {
        if (config('app.enable_cache_headers')) {
            $options = collect(config('app.cache_headers_options', []))->join(';');

            if (! $options) {
                return;
            }

            Route::pushMiddlewareToGroup('api', "cache.headers:{$options}");
        }
    }
}
