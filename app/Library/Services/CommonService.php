<?php

namespace App\Library\Services;
use Illuminate\Support\Facades\Crypt;


class CommonService{

    public static function DeviceAPIEncrypt($bearerToken){
        return Crypt::encryptString($bearerToken);
        //return hash('sha256', $bearerToken);
    }

    public static function DeviceAPIDecrypt($str){
        try {
            $decrypted = Crypt::decryptString($str);
            return (Object)["result"=>"success", "decryptedString"=>$decrypted];
        } catch (DecryptException $e) {
            return (Object)["result"=>"fail", "decryptedString"=>""];
        }
    }



}