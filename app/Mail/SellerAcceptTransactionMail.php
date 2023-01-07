<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerAcceptTransactionMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public $secondary_email;
    public $email;
    public $transaction;

    public function __construct($secondary_email, $email, $transaction)
    {

        $this->secondary_email = $secondary_email;
        $this->email = $email;
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hello@paylidate.com', 'Paylidate')
        ->subject('Review and accept transaction terms')->view('mails.seller_accept_transaction');
    }
}
