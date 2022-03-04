<?php
namespace App\Library\Services;
use App\Models\User;
use App\Models\DeviceList;
use Illuminate\Support\Facades\Auth;
use App\Models\DeviceOwnershipShare;
use App\Library\Services\MessageService;
use App\Models\MobileAccess;
class APIQueryService
{

    //A Way To Filter All irrelevant params
    function __construct($paramToFieldArray) {
       $this->paramToFieldArray=$paramToFieldArray;
    }

    /*paramToFieldArray=[
        "QueryParameter"=>"Field"
    ]*/

    
    public function toDBUpdateArray($queryParamValuePairs){
        $fieldValuePairs=[];
        foreach ($queryParamValuePairs as $param => $value) {
            if (array_key_exists($param, $this->paramToFieldArray)) {
                
                //array_push($fieldValuePairs,[ ($this->paramToFieldArray)[$param]=>$value]);

                if ($param != null && $value != null) {
                    $fieldValuePairs[$this->paramToFieldArray[$param]]=$value;
                }
            }
        }
        return $fieldValuePairs;

    }










}