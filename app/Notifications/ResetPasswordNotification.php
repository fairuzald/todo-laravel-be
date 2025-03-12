<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
  /**
   * The password reset token.
   *
   * @var string
   */
  public $token;

  /**
   * Create a new notification instance.
   *
   * @param  string  $token
   * @return void
   */
  public function __construct($token)
  {
    $this->token = $token;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param  mixed  $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    $url = config('app.frontend_url', config('app.url')) . '/reset-password?token=' . $this->token . '&email=' . urlencode($notifiable->email);

    return (new MailMessage)
      ->subject('Reset Password Notification')
      ->line('You are receiving this email because we received a password reset request for your account.')
      ->action('Reset Password', $url)
      ->line('This password reset link will expire in 60 minutes.')
      ->line('If you did not request a password reset, no further action is required.');
  }
}
