<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetNotification extends Notification
{
    use Queueable;

    protected $code;

    /**
     * Create a new notification instance.
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Password Reset Code - URS Binangonan')
            ->greeting('Hello ' . $notifiable->first_name . ' ' . $notifiable->last_name . '!')
            ->line('We received a request to reset your password for your IPCR System account.')
            ->line('Please use the following verification code to reset your password:')
            ->line('# **' . $this->code . '**')
            ->line('This code will expire in **15 minutes**.')
            ->line('If you did not request a password reset, please ignore this email. Your password will remain unchanged.')
            ->salutation('Best regards,')
            ->salutation('University of Rizal System - Binangonan Campus')
            ->salutation('IPCR System Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
