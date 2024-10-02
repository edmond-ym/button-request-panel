<?php
namespace App\Library\Interface;

interface UserRightOnMobileTokenServiceInterface
{

    public static function right ($mobile_token_case_id, $userId=null);
    
}