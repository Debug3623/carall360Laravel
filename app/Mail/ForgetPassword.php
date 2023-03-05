<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\User;


class ForgetPassword extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $oUser;
    public $iVerificationCode;
    public $sIdentifier;
    public $iExpiryMin;

    public function __construct($user, $identifier, $verification_code, $iExpiry)
    {
        $this->oUser = $user;
        $this->iVerificationCode = $verification_code;
        $this->sIdentifier = $identifier;
        $this->iExpiryMin = $iExpiry;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.forget.password')->with([
            'code' =>  encrypt($this->iVerificationCode),
            'identifier' => encrypt($this->sIdentifier),
            'expiry_after' => $this->iExpiryMin,
            'name' => $this->oUser->name
        ])->subject("Forgot Password");
    }
}
