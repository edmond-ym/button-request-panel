<?php

namespace App\Library\Interface;

interface CommonServiceInterface{

    public static function DeviceAPIEncrypt($bearerToken);
    public static function DeviceAPIDecrypt($str);

    public static function ObjectInArrayMustHaveKey($dataArray, $MustHaveKeyArray);


}