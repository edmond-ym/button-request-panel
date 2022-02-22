<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileAccess extends Model
{
    use HasFactory;
    protected $table = 'mobile_access';
    protected $primaryKey = 'access_token';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
}
