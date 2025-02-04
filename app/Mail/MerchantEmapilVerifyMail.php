<?php

namespace App\Mail;

use App\Http\Traits\MessageTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MerchantEmapilVerifyMail extends Mailable
{
    use Queueable, SerializesModels, MessageTrait;
    public $MerchantEmail,$code,$GlobalSettings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($MerchantEmail,$otp,$GlobalSettings)
    {
        $this->MerchantEmail = $MerchantEmail;
        $this->code = $otp;
        $this->GlobalSettings = $GlobalSettings;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->InfoMailFrom, $this->WebSiteName),
            subject: $this->MerchantEmailVerifyMailSubject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'mail.MerchantEmailVerify',
            with: [
                'subject' => $this->MerchantEmailVerifyMailSubject
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
