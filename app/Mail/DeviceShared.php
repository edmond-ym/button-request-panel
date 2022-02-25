<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\DeviceOwnershipShare;
use App\Models\DeviceList;

class DeviceShared extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $receiverType; //sharer or sharee
    protected $sharerUserId;
    protected $shareeUserId;
    protected $deviceId;
    public function __construct($receiverType, $sharerUserId, $shareeUserId, $deviceId)
    {
        $this->receiverType=$receiverType;
        $this->sharerUserId=$sharerUserId;
        $this->shareeUserId=$shareeUserId;
        $this->deviceId=$deviceId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.deviceShared')->with([
            'receiverType'=>$this->receiverType,
            'sharerFullName'=>User::find($this->sharerUserId)->name,
            'sharerEmail'=>User::find($this->sharerUserId)->email,
            'shareeFullName'=>User::find($this->shareeUserId)->name,
            'shareeEmail'=>User::find($this->shareeUserId)->email,
            'deviceInfo'=>(Object)["deviceId"=>$this->deviceId,
             "nickname"=>DeviceList::where("device_id", '=', $this->deviceId)->get()[0]->nickname, 
        ]
        ]);
    }
}
