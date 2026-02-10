<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
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
            ->subject('Verify Your Email Address - URS IPCR System')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for registering with the IPCR System.')
            ->line('Please use the following verification code to verify your email address:')
            ->line('# **' . $this->code . '**')
            ->line('This code will expire in **30 minutes**.')
            ->line('If you did not create an account, please ignore this email.')
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
