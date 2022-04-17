<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    //public $afterCommit = true;
    public $user;
    public $verifyEmailLink;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $verifyEmailLink)
    {
        $this->user = $user;
        $this->verifyEmailLink = $verifyEmailLink;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('paylidatesupport@lotusfort.com', 'Paylidate')
        ->subject('Verify Email')->view('mails.registration');
        
        //$this->view('mails.registration');
    }
}
