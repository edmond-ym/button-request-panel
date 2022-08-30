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

    public static function ObjectInArrayMustHaveKey($dataArray, $MustHaveKeyArray): Array{
        $newDataArray=$dataArray;
        for ($i=0; $i < count($dataArray); $i++) { 
            $element=$dataArray[$i];
            for ($j=0; $j < count($MustHaveKeyArray); $j++) { 
                $key1=$MustHaveKeyArray[$j];
                if (!array_key_exists($key1, $element)) {
                    $newDataArray[$i][$key1]="";
                }
            }
        }
        return $newDataArray;
    }


}