<?php

namespace App\Mail;

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionAcceptedMail extends Mailable
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
        return $this->subject('Transaksi Anda Telah Diterima')
            ->view('emails.transaction.accepted')
            ->with(['notes' => $this->notes]);
    }
}
