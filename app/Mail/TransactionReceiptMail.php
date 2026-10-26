<?php

namespace App\Mail;

use App\Models\Transaction; // Sudah di-import di sini
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var Transaction
     */
    public Transaction $transaction; // Ubah \App\Models\Transaction menjadi Transaction saja

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction) // Gunakan tipe Transaction
    {
        $this->transaction = $transaction;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Struk Pembayaran Transaksi #' . $this->transaction->id,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'reports.pdf',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}