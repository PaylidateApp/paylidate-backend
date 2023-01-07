<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FulfilmentMail extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $data;
    public $transaction;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $data, $transaction)
    {
        $this->user = $user;
        $this->data = $data;
        $this->transaction = $transaction;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hello@paylidate.com', 'Paylidate')->view('mails.fulfilment');
    }
}
