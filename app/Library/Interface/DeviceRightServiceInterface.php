<?php

namespace App\Library\Interface;
 

interface DeviceRightServiceInterface
{
    public static function AbsoluteRightOnDevice($device_id, $userId=null);
    public static function SharedDeviceAdvancedRight($user_id, $device_id);
    public static function ShareDeviceBasicRight($user_id, $device_id);
    public static function SharedDeviceMiddleRight($user_id, $device_id);
}