<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\BroadcastsEvents;

class MessageEnquiry extends Model
{
    use HasFactory, BroadcastsEvents;
    protected $table = 'message';
    protected $primaryKey = 'msg_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    /*public function broadcastOn($event)
    {
        //return [$this, $this->user];
    }*/

}
