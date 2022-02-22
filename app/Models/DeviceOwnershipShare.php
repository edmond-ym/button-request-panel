<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DeviceList;

class DeviceOwnershipShare extends Model
{
    use HasFactory;
    protected $table = 'device_ownership_share';
    protected $primaryKey = 'case_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    
}
