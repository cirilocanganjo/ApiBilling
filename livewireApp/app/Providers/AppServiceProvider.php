<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{  
  
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
  

    RateLimiter::for('api', function (Request $request) {
    $max = config('api.rate_limit');
    $minutes = config('api.rate_limit_minutes');

    return Limit::perMinutes($minutes, $max)
        ->by(optional($request->user())->id ?: $request->ip());
    });

    }
}
