<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerDisputeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $user;
    public $transaction;
    public $dispute;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $dispute, $transaction, $user)
    {

        $this->subject = $subject;
        $this->user = $user;
        $this->transaction = $transaction;
        $this->dispute = $dispute;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->from('hello@paylidate.com', 'Paylidate')
        ->subject('Dispute ('.$this->subject .')' )->view('mails.seller_dispute');
        
    }
}
