<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\MarketplaceStore;
use Illuminate\Support\Facades\Session;

class CheckStoreSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $userId = Session::get('id');
        
        if (!$userId) {
            return redirect('/login')->with('error', 'You must be logged in.');
        }

        $store = MarketplaceStore::where('owner_id', $userId)->first();

        if (!$store) {
            return redirect()->route('marketplace.show-create-store')
                ->with('error', 'You need to create a store first.');
        }

        // Check if subscription is expired
        if ($store->isSubscriptionExpired()) {
            // Allow access to renewal and view pages only
            $allowedRoutes = [
                'marketplace.renew-subscription',
                'marketplace.process-renewal',
                'marketplace.renewal-success',
                'marketplace.my-store',
                'marketplace.index'
            ];

            if (!in_array($request->route()->getName(), $allowedRoutes)) {
                return redirect()->route('marketplace.renew-subscription')
                    ->with('error', 'Your store subscription has expired. Please renew to continue.');
            }
        }

        // Warning if expiring soon (within 7 days)
        $daysRemaining = $store->daysUntilExpiry();
        if ($daysRemaining !== null && $daysRemaining >= 0 && $daysRemaining <= 7) {
           // NEW CODE (Using the Session Facade):
            \Session::flash('subscription_warning',
                "Your store subscription expires in {$daysRemaining} day(s). Please renew soon to avoid interruption."
            );
        }

        return $next($request);
    }
}



