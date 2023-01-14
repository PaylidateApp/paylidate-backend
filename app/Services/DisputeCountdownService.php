<?php

namespace App\Services;

use App\Transaction;
use App\Dispute;
use Carbon\Carbon;

class DisputeCountdownService
{
    public function countdown($dispute){
        $countdown_date = Carbon::now()->addDays(7);
        while (Carbon::now()->lt($countdown_date)) {
            // do nothing
        }
        if ( $dispute == true) {
            return true;
        }
    }
}