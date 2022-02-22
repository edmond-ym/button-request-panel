<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory;
    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    public $incrementing = true;
    //protected $keyType = 'string';
    public $timestamps = true;
}
