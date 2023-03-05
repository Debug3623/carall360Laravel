<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


use App\User;
use phpDocumentor\Reflection\Types\This;

class InvoiceAlert extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $sSubject;
    public $sMessage;

    
    public function __construct($sSubject, $txtMessage)
    {

        //dd($this);
        $this->sSubject = $sSubject;
        $this->sMessage = $txtMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.general.invoice')->with([
            'msg' =>  $this->sMessage,
            'subject' => $this->sSubject            
        ])->subject($this->sSubject);
    }
}
