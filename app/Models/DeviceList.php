<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MessageEnquiry;
use App\Models\DeviceOwnershipShare;

class DeviceList extends Model
{
    use HasFactory;
    protected $table = 'device_list';
    protected $primaryKey = 'case_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    
    public function messageEnquiry(){
        return $this->hasMany(MessageEnquiry::class, 'device_case_id', 'case_id');
    }
    public function deviceOwnership(){
        return $this->hasMany(DeviceOwnershipShare::class, 'device_id', 'device_id');
    }
    public static function CorrespondingDeviceId($case_id){
        $d=DeviceList::where('case_id', $case_id)->get();
        return $d[0]['device_id'];
    }

}
