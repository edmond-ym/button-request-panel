<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\SubscriptionManagementController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Library\Services\SubscriptionManagementService;

class SubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    
    //public static $testMode=false;
    public function handle(Request $request, Closure $next/*, $api=false*/)
    {
        
        $stripeCustomer = Auth::user()->createOrGetStripeCustomer();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        //Account Status
        if ($stripeCustomer->deleted) {
            User::where('id','=',Auth::id())->update(['stripe_id'=> null]);
            $stripeCustomer = Auth::user()->createOrGetStripeCustomer();
        }

        //Status Check
        //if (!self::$testMode) {
            /*if (!SubscriptionManagementService::subscribed(Auth::id())) {
                return redirect(route('subscription_dashboard_ui'));
            }*/
            //echo $api;
            if (!SubscriptionManagementService::offlineStatusSubscribed(Auth::id())) {
                /*if ($api) {
                    return response()->json(['result'=>'not-subscribed']);
                }*/
                return redirect(route('subscription_dashboard_ui'));
            }

        //}
        return $next($request);
    }

    
}
