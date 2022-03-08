<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Library\Services\SubscriptionManagementService;

class SubscriptionManagementController extends Controller
{
    //
    //currentSubscriptionData//
    //subscriptionType//
    //subscriptionCountByPlanType
    //subscriptionChoice//
    //subscriptionChoiceByPriceId
    //subscriptionIDList//
    //subscriptionItemList//
    //subscriptionCountByPriceID
    function __construct(){
       
    }

    public function subscription_dashboard_ui(Request $request){
        SubscriptionManagementService::offlineSubscriptionStatusUpdate(Auth::id());
        $prodId='prod_L4b1U9tMq5UtiY';
        $user=Auth::user();
        
        $stripeCustomer = $user->createOrGetStripeCustomer();
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
   
        $SubscriptionData=SubscriptionManagementService::currentSubscriptionData(Auth::id());
        return view("dashboard.subscriptionManagement", [
            'intent' => $user->createSetupIntent(),
            'currentPaymentMethod'=>$user->paymentMethods(),
            'defaultPaymentMethod'=>$user->defaultPaymentMethod(),
            'retrieveCurrentSubscriptions'=>$SubscriptionData,
            'hasPaymentMethod'=>$user->hasPaymentMethod(),
            'subscriptionItemList'=>SubscriptionManagementService::subscriptionItemList(Auth::id(), $SubscriptionData),
            'currentSubscriptionType'=>SubscriptionManagementService::subscriptionType(Auth::id(), $SubscriptionData),
            //'subscriptionIDList'=>SubscriptionManagementService::subscriptionIDList(Auth)
            
            //'deviceMeterBillSubscribed'=>$user->subscribed('default')),
            //'deviceMeterBillSubscribed'=>$user->subscribed('default'))

            
        ]);
    }
    
    public function create_new_user(){
        $user=Auth::user();
        $option=[
            'email'=>$user->email,
            'name'=>$user->name
        ];
        $stripeCustomer = $user->createAsStripeCustomer($option);
    }

    public function add_new_setup_intent(Request $request){
        $user=Auth::user();
        $setup_intent=$request->input('setup_intent');
        $setup_intent_client_secret=$request->input('setup_intent_client_secret');

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $setup_intent_object_fetch=$stripe->setupIntents->retrieve(
          $setup_intent,
          []
        );
        
        $user->addPaymentMethod($setup_intent_object_fetch->payment_method);
        return redirect(url()->previous());
        
    }
    public function delete_payment_method(Request $request){
        $validator=Validator::make($request->all(), [
            'delete_payment_method' => 'required'
        ]);
        $user=Auth::user();

        $stripe = new \Stripe\StripeClient(
            env('STRIPE_SECRET')
        );
        $validated= $validator->validate();

        if(!$validator->fails() && Auth::check()){
            if (SubscriptionManagementService::rightOnPaymentMethod(Auth::id(), $validated['delete_payment_method'])) {
                $stripe->paymentMethods->detach(
                    $validated['delete_payment_method'],
                    []
                );
            }
        }
        SubscriptionManagementService::offlineSubscriptionStatusUpdate(Auth::id());
        return redirect(url()->previous());
    }
    public function set_default_payment_method(Request $request){
        
        $validator=Validator::make($request->all(), [
            'set_as_default' => 'required'
        ]);

        $validated= $validator->validate();

        if(!$validator->fails() && Auth::check()){
            $user=Auth::user();
            
            $paymentMethod = $user->findPaymentMethod($validated['set_as_default']);
            
            $user->updateDefaultPaymentMethod($paymentMethod );
        }
        SubscriptionManagementService::offlineSubscriptionStatusUpdate(Auth::id());
        return redirect(url()->previous());
    }

    public function subscribe_service(Request $request){
        $validator=Validator::make($request->all(), [
            'subscribe_item' => 'required',
            'payment_method_select'=>'required'
        ]);

        $validated= $validator->validate();

        if(!$validator->fails() && Auth::check()){
            $payment_method_selected=$validated['payment_method_select'];
            $subscribeItem=$validated['subscribe_item'];
            $user=Auth::user();
            
            $stripe = new \Stripe\StripeClient(
                env('STRIPE_SECRET')
            );
            $stripe->subscriptions->create([
              'customer' => $user->asStripeCustomer()->id,
              'items' => [
                ['price' => SubscriptionManagementService::subscriptionChoice($subscribeItem)],
              ],
              'default_payment_method'=>$payment_method_selected
            ]);
            /*$request->user()->newSubscription(
                'basic'
            )->meteredPrice(SubscriptionManagementService::subscriptionChoice($subscribeItem))
             ->create($payment_method_selected);*/
        }
        SubscriptionManagementService::offlineSubscriptionStatusUpdate(Auth::id());
    
    
        return redirect(url()->previous());
    }
   

    /*public static function hasActiveSubscriptionBR(){
        $data=self::currentSubscriptionData();
    }*/
   

    

    public function cancelSubscriptionItem($SubId){
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        if (SubscriptionManagementService::rightOnSubscription(Auth::id(), $SubId)) {
            $subscription = \Stripe\Subscription::retrieve($SubId);
            $subscription->cancel();
        }
        SubscriptionManagementService::offlineSubscriptionStatusUpdate(Auth::id());
        return redirect(url()->previous());
    }

    public static function subscriptionRetrieve($subscriptionId){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        if (SubscriptionManagementService::rightOnSubscription(Auth::id(), $subscriptionId)) {
            return $stripe->subscriptions->retrieve(
              $subscriptionId,
              []
            ); 
        }

        return [];
        
    }

    public static function paymentMethodRetrieve($PaymentMethodId){
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        if (SubscriptionManagementService::rightOnPaymentMethod(Auth::id(), $PaymentMethodId)) {
            return $stripe->paymentMethods->retrieve(
                $PaymentMethodId,
                []
            );
        }
        return [];
    }

    public function paymentMethodUpdate(Request $request){
        $validator=Validator::make($request->all(), [
            'SubId' => 'required',
            'payment_method_select'=>'required'
        ]);

        $validated= $validator->validate();

        if(!$validator->fails() && Auth::check()){
           /* $user=Auth::user();
            
            $paymentMethod = $user->findPaymentMethod($validated['set_as_default']);
            
            $user->updateDefaultPaymentMethod($paymentMethod );*/
            if (SubscriptionManagementService::rightOnSubscription(Auth::id(), $validated['SubId']) && SubscriptionManagementService::rightOnPaymentMethod(Auth::id(), $validated['payment_method_select'])) {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $stripe->subscriptions->update(
                  $validated['SubId'],
                  ['default_payment_method' => $validated['payment_method_select']]
                );
            }
        }
        return redirect(url()->previous());
    }
    public function changePlan(Request $request){
        $validator=Validator::make($request->all(), [
            'newPlan' => 'required',
            'SubItemId'=>'required'
        ]);

        $validated= $validator->validate();

        if(!$validator->fails() && Auth::check()){
            if (SubscriptionManagementService::rightOnSubscriptionItem(Auth::id(), $validated['SubItemId'])) {
                $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
                $stripe->subscriptionItems->update(
                  $validated['SubItemId'],
                  ['price' => SubscriptionManagementService::subscriptionChoice($validated['newPlan'])]
                );
            }

        }
        return redirect(url()->previous());
    }



   
        

}



