<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;

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
       
        RateLimiter::for('virtual_accounts', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip())->response(function () {
                Log::info('session', context: ['ses' => 'many errors']);
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too many requests. Please try again later.',
                ], 429);
            });
            
        });

        
    }
}
