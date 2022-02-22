<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;

class SubscriptionManagementService
{
    function __construct() {
       
    }

     //Right on PM
     public static function rightOnPaymentMethod($userId, $PaymentId){
        $cus_id=User::find($userId)->stripe_id;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $pm_belonger=$stripe->paymentMethods->retrieve(
            $PaymentId,
            []
        )->customer;
        if ($cus_id==$pm_belonger) {
            return true;
        }
        return false;
    }
    //Right On Sub
    public static function rightOnSubscription($userId, $SubId){
        $cus_id=User::find($userId)->stripe_id;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $SubBelonger=$stripe->subscriptions->retrieve(
            $SubId,
            []
        )->customer;
        if ($SubBelonger==$cus_id) {
            return true;
        }
        return false;
    }
    //Right On SubItem
    public static function rightOnSubscriptionItem($userId, $SubItemId){
        $cus_id=User::find($userId)->stripe_id;
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $CorrSubId=$stripe->subscriptionItems->retrieve(
            $SubItemId,
            []
        )->subscription;
        return self::rightOnSubscription($userId, $CorrSubId);
    }

     
    public static function currentSubscriptionData($userId){//ok

        $cus_id=User::find($userId)->stripe_id;
        
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

        
        return ($stripe->subscriptions->all([
            'customer' => $cus_id
        ]))->data;
        
    }


    
    public static function subscriptionCountByPlanType($userId, $subData){//ok

        $countByType=(object)[
            'basic' => self::subscriptionCountByPriceID($userId, self::subscriptionChoice('basic'), $subData)
        ];
        return $countByType;
    }
    
    public static function subscriptionType($userId, $subData){//ok
        $type="none";
        if (self::subscriptionCountByPlanType($userId, $subData)->basic>0) {
            $type="basic";
        }
        return $type;
    }
    public static function subscriptionChoice($type){//ok
        $subscription_choice=[
            'basic'=>'price_1KORpVGYbHDUMvIftMiGRm3i',
        ];
        return $subscription_choice[$type];
    }
    public static function subscriptionChoiceByPriceId($PriceID){//ok
        $subscription_choice=[
            'price_1KORpVGYbHDUMvIftMiGRm3i'=>'basic'
        ];
        return $subscription_choice[$PriceID];
    }
    

    ////
    public static function subscriptionIDList($userId, $subData){//ok
        $output_arr=[];
        for ($i=0; $i < count($subData); $i++) { 
            array_push($output_arr, $subData[$i]->id);
        }
        return $output_arr;
    }
    
    public static function subscriptionItemList($userId, $subData){// ok
        $output_arr=[];
       
        for ($i=0; $i < count($subData); $i++) { 

            $d1=$subData[$i]->items->data;
            for ($j=0; $j < count($d1); $j++) { 
                array_push($output_arr, $d1[$j]);
            }
        }
        return $output_arr;
    }
    public static function subscriptionCountByPriceID($userId, $priceID, $subData){//ok

        $count=0;
        $subscriptionItemList=self::subscriptionItemList($userId, $subData);
        for ($i=0; $i < count($subscriptionItemList); $i++) { 
            if ($subscriptionItemList[$i]->plan->id==$priceID) {
                $count += 1;
            }
        }
        return $count;
    }
    public static function subscribed($userId){
        
        
        $subData=self::currentSubscriptionData($userId);
        if (self::subscriptionType($userId, $subData) !="none") {
            if (self::subscriptionItemList($userId, $subData)[0]->plan->active) {
                return true;
            } 
        }
           
        return false;
    }
    public static function offlineSubscriptionStatusUpdate($userId){
        if (self::subscribed($userId)) {
            User::where('id', '=', $userId)->update(['subscription_status'=>'active']);
        }else{
            User::where('id', '=', $userId)->update(['subscription_status'=>'not-active']);
        }
    }
    public static function offlineStatusSubscribed($userId){
        $testMode=false;
        if (!$testMode) {
            if (User::where('id', '=',$userId)->get()[0]->subscription_status_updated=='false') {
                self::offlineSubscriptionStatusUpdate($userId);
                User::where('id', '=',$userId)->update(['subscription_status_updated'=>'true']);
            }
  
            $d=User::where('id', '=',$userId)->get();
            //echo $d[0]->subscription_status;
            if ($d[0]->subscription_status=='active') {
                return true;
            } 
            return false; 
        }else{
            return true;
        }
        
    }

    
    
}