<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // DON'T call Broadcast::routes() here since we're using a custom controller
        // Broadcast::routes(['middleware' => ['web']]);

        // Still load channel definitions (though we won't use them with custom auth)
        require base_path('routes/channels.php');
        
        Log::info('BroadcastServiceProvider booted - using custom auth');
    }
}