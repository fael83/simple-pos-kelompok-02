<?php

namespace App\Jobs;

use App\Models\Transaction; // Sudah di-import di sini
use App\Mail\TransactionReceiptMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTransactionReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var Transaction
     */
    public Transaction $transaction; // Ubah \App\Models\Transaction menjadi Transaction saja

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction) // Gunakan tipe Transaction
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->transaction->customer_email ?? 'customer@example.com')
            ->send(new TransactionReceiptMail($this->transaction));
    }
}