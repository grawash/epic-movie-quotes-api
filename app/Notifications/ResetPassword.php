<?php

namespace App\Notifications;

use Illuminate\Support\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

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
		// $resetUrl = $this->ResetUrl($notifiable);
		// $url = url('http://127.0.0.1:5173/', [
		// 	'token' => $this->token,
		// 	'email' => $notifiable->getEmailForPasswordReset(),
		// ]);
		return (new MailMessage)
			->line('You are receiving this email because we received a password reset request for your account.')
			->action('Reset Password', url('http://127.0.0.1:5173/') . '?token=' . urlencode($this->token) . '&email=' . urlencode($notifiable->email)); // add this. this is $actionUrl
	}

	// protected function ResetUrl($notifiable)
	// {
	// 	return 'http://127.0.0.1:5173/?' . http_build_query(
	// 		[
	// 			'ResetPassword' => URL::temporarySignedRoute(
	// 				'password.email',
	// 				Carbon::now()->addMinutes(Config::get('auth.ResetPassword.expire', 60)),
	// 				[
	// 					'token'   => $this->token,
	// 					'email'   => $notifiable->getEmailForPasswordReset(),
	// 				]
	// 			),
	// 		]
	// 	);
	// }

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
