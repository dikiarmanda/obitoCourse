<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Helper\TransactionHelper;

class TransactionObserver
{
    public function creating(Transaction $transaction): void
    {
        $transaction->booking_trx_id = TransactionHelper::generateBookingTrxId();
    }

    public function created(Transaction $transaction): void
    {

    }

    public function updated(Transaction $transaction): void
    {

    }

    public function deleted(Transaction $transaction): void
    {

    }

    public function restored(Transaction $transaction): void
    {

    }
}
