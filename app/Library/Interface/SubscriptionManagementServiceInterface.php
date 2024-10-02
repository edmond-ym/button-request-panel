<?php
namespace App\Library\Interface;

interface SubscriptionManagementServiceInterface
{
   
    public static function rightOnPaymentMethod($userId, $PaymentId);
    public static function rightOnSubscription($userId, $SubId);
    public static function rightOnSubscriptionItem($userId, $SubItemId);

     
    public static function currentSubscriptionData($userId);


    
    public static function subscriptionCountByPlanType($userId, $subData);
    
    public static function subscriptionType($userId, $subData);
    
    public static function subscriptionChoice($type);
    
    public static function subscriptionChoiceByPriceId($PriceID);


    public static function subscriptionIDList($userId, $subData);
    
    public static function subscriptionItemList($userId, $subData);
    public static function subscriptionCountByPriceID($userId, $priceID, $subData);
    public static function subscribed($userId);
    public static function offlineSubscriptionStatusUpdate($userId);
    public static function offlineStatusSubscribed($userId);

    
    
}