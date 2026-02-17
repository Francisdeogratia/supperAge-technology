<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $isUserCopy;

    public function __construct($data, $isUserCopy = false)
    {
        $this->data = $data;
        $this->isUserCopy = $isUserCopy;
    }

    public function build()
    {
        if ($this->isUserCopy) {
            // Email to user (confirmation)
            return $this->subject('Thank you for contacting SupperAge')
                        ->view('emails.contact-confirmation');
        }

        // Email to admin
        return $this->subject('New Contact Form Submission - ' . $this->data['subject'])
                    ->replyTo($this->data['email'], $this->data['name'])
                    ->view('emails.contact-form');
    }
}