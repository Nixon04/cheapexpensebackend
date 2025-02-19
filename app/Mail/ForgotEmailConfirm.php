<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotEmailConfirm extends Mailable
{
    use Queueable, SerializesModels;

    public $tokencode;

    /**
     * Create a new message instance.
     */
    public function __construct($tokencode)
    {
        $this->tokencode = $tokencode;
        //
    }


    public function build(){
        return $this->subject('Verify User Information')->markdown('emails.forgotpassword');
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
