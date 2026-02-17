<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BadgeRenewalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $status;
    protected $currency;
    protected $amount;

    public function __construct($status, $currency, $amount)
    {
        $this->status = $status; // success or failed
        $this->currency = $currency;
        $this->amount = $amount;
    }

    public function via($notifiable)
    {
        return ['mail','database']; // email + in-app
    }

    public function toMail($notifiable)
    {
        $mail = new MailMessage;
        if ($this->status === 'success') {
            $mail->subject('Blue Badge Auto-Renewed ✅')
                 ->greeting("Hello {$notifiable->name},")
                 ->line("Your Blue Badge has been successfully renewed.")
                 ->line("We deducted {$this->currency} {$this->amount} from your wallet.")
                 ->line("Your badge is now valid for another 30 days.")
                 ->salutation('Thank you for staying verified!');
        } else {
            $mail->subject('Blue Badge Renewal Failed ⚠️')
                 ->greeting("Hello {$notifiable->name},")
                 ->line("We attempted to auto-renew your Blue Badge but your {$this->currency} wallet did not have enough funds.")
                 ->line("Please top up your wallet or renew manually to keep your badge active.")
                 ->salutation('Stay secure with SupperAge!');
        }
        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'status' => $this->status,
            'currency' => $this->currency,
            'amount' => $this->amount,
            'message' => $this->status === 'success'
                ? "Your badge was auto-renewed with {$this->currency} {$this->amount}."
                : "Auto-renew failed due to low {$this->currency} balance."
        ];
    }
}

