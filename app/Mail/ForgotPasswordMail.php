<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPasswordMail extends Mailable
{    use Queueable, SerializesModels;
    
    public $user;
    public $passwordResetLink;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $passwordResetLink)
    {
        $this->user = $user;
        $this->passwordResetLink = $passwordResetLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('no_reply@paylidate.com', 'Paylidate')
        ->subject('Reset Paasword')->view('mails.forgot_password');
    }
}
