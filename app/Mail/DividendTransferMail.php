<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\Transfer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DividendTransferMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transfer $transfer;
    public ?Setting $setting;

    /**
     * Create a new message instance.
     */
    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer->loadMissing(['investor.investors', 'investor.investor', 'admin']);
        $this->setting = Setting::first();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $code = $this->transfer->code ?: ('INV-TRF-' . $this->transfer->id);
        $fromAddress = config('mail.from.address', 'finance@cionetworksolution.com');
        $fromName = config('mail.from.name', config('app.name', 'CIO Investor Portal'));

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: "Pemberitahuan Transfer Dividen Bagi Hasil [{$code}] - " . ($this->setting->company_name ?? 'CIO Network Solution'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dividend_transfer',
            with: [
                'transfer'   => $this->transfer,
                'investor'   => $this->transfer->investor,
                'setting'    => $this->setting,
                'invoiceUrl' => $this->transfer->getInvoiceUrl(),
            ],
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
