<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportTransaction extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user_name;
    public $type;
    public $transaction_ref;

    public function __construct($user_name, $transaction_ref, $type)
    {

        $this->user_name = $user_name;
        $this->type = $type;
        $this->transaction_ref = $transaction_ref;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('hello@paylidate.com', 'Paylidate')
        ->subject('Report Transaction')->view('mails.report_transaction');
    }

}
