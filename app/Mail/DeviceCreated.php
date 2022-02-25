<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DeviceCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $newCredential;
    protected $fullName;
    protected $deviceCredential;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fullName, $newCredential, $deviceCredential)
    {
        $this->newCredential=$newCredential;
        $this->fullName=$fullName;
        $this->deviceCredential=$deviceCredential;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('mail.deviceCreated')->with([
            'fullName'=>$this->fullName,
            'newCredential' =>$this->newCredential,
            'deviceCredential'=>$this->deviceCredential,
            'currentTime'=>gmdate("Y-m-d H:i:s P")
        ])/*->markdown('view-to-mail')*/;
        
    }
}
