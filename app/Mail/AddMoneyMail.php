<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddMoneyMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $name;
    public $amount;
    public $currency;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $amount, $currency)
    {
        $this->name = $name;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.add_money');
    }
}
