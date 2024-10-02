<?php
namespace App\Library\Interface;

interface MobileAccessServiceInterface
{
   
    public static function NewMobileAccess($userId,$nickname);
    public static function MobileAccessDestroy($userId, $case_id);
    public static function MobileAccessAmend($userId, $case_id, $updateArray);

  
}