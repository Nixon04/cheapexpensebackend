<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifyPinEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $passwordpin;
    public $username;


    /**
     * Create a new message instance.
     */
    public function __construct($email, $passwordpin, $username)
    {
        $this->email = $email;
        $this->passwordpin = $passwordpin;
        $this->username = $username;
        //
    }

     public function build(){
      return $this->subject('Verify User Information')->markdown('emails.verifypinemail');
     }  
 
    public function attachments(): array
    {
        return [];
    }
}
