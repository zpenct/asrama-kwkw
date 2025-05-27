<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;

    public ?string $notes;

    public function __construct(Transaction $transaction, ?string $notes = null)
    {
        $this->transaction = $transaction;
        $this->notes = $notes;
    }

    public function build(): self
    {
        return $this->subject('Transaksi Anda Ditolak')
            ->view('emails.transaction.rejected')
            ->with(['notes' => $this->notes]);
    }
}
