<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDeviceLoginAlert extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
public string $ip;
public string $agent;
public object $user;  // Add this line
public string $token; // Optional, if you're passing a token to the email
public function __construct(string $ip, string $agent,object $user, string $token)
{
    $this->ip = $ip;
    $this->agent = $agent;
    $this->user = $user;
    $this->token = $token;
}

public function envelope(): Envelope
{
    return new Envelope(
        subject: 'New Device Login Alert',
    );
}

public function content(): Content
{
    return new Content(
        view: 'emails.new_login',
        with: [
            'ip' => $this->ip,
            'agent' => $this->agent,
            'user' => $this->user,
            'token' => $this->token
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
