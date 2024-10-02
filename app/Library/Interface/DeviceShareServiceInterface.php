<?php
namespace App\Library\Interface;

interface DeviceShareServiceInterface{
    public static function GiveUpShareeRight($userId, $case_id);
    public static function ShareNewDevice($userId, $shareToEmail, $deviceId);
    public static function changeShareeRight($userId, $case_id, $new_right);
    public static function revokeShareeRight($userId, $case_id );
}