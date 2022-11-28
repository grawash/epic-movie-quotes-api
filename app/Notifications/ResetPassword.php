<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
	use Queueable;

	public $token;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($token)
	{
		$this->token = $token;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable)
	{
		return (new MailMessage)
			->line('You are receiving this email because we received a password reset request for your account.')
			->action('Reset Password', url(config('auth.front_end_full_domain')) . '?token=' . urlencode($this->token) . '&email=' . urlencode($notifiable->email)); // add this. this is $actionUrl
	}

	/*
		 * Get the array representation of the notification.
		 *
		 * @param  mixed  $notifiable
		 * @return array
		 */
	// public function toArray($notifiable)
	// {
	//     return [
	//         //
	//     ];
	// }
}
