<?php
namespace App\Library\Interface;

interface DeviceListServiceInterface
{
    public static function newDeviceGenerate($user_id, $nickname);
    public static function repeatMessageAllowUpdate($user_id,$device_id, $allowOrDisallow);
    public static function buttonMessageConfigure($user_id, $device_id, $action, $passData);       
    public static function trimArrayByObjectKeyValue($oldArray, $ValueToTrimArray, $key);
    public static function addOrUpdateObjectInArray($oldArray, $ObjectArrayUpdateOrCreate,$existDetermineKey, $requiredUpdateKey, $keyAllowedArray);
    public static function ArrayObjectKeyValueExist($array, $keyName, $keyValue);
    public static function ArrayPushNewObject($oldArray, $ObjectToPush);
    public static function ArrayUpdateObject($oldArray, $conditionKeyName, $conditionKeyValue, $ObjectToReassign);
    public static function ObjectInArrayValid($array, $requiredKeyArray);
    public static function ArrayItemisString($array);
}


   