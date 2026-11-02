<?php

namespace App\Providers;

use App\OpenApi\AuthOperationTransformer;
use Dedoc\Scramble\Scramble;
use Illuminate\Routing\Route;
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
        Scramble::configure()
            ->routes(fn (Route $route): bool => str_starts_with($route->uri(), 'api/')
                || in_array($route->uri(), AuthOperationTransformer::PATHS, true))
            ->withOperationTransformers(AuthOperationTransformer::class);
    }
}
