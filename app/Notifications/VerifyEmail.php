<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
	use Queueable;

	/**
	 * The callback that should be used to build the mail message.
	 *
	 * @var \Closure|null
	 */
	public static $toMailCallback;

	// /**
	//  * Create a new notification instance.
	//  *
	//  * @return void
	//  */
	// public function __construct()
	// {
	//     //
	// }

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
		$verificationUrl = $this->verificationUrl($notifiable);

		if (static::$toMailCallback)
		{
			return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
		}

		return (new MailMessage)
			->subject(Lang::get('Verify Email Address bro'))
			->line(Lang::get('Please click the button below to verify your email address bro.'))
			->action(Lang::get('Verify Email Address bro'), $verificationUrl)
			->line(Lang::get('If you did not create an account, no further action is required bro.'));
	}

	/**
	 * Get the verification URL for the given notifiable.
	 *
	 * @param mixed $notifiable
	 *
	 * @return string
	 */
	protected function verificationUrl($notifiable)
	{
		return 'http://127.0.0.1:5173/?' . http_build_query(
			[
				'verifyLink' => URL::temporarySignedRoute(
					'verification.verify',
					Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
					[
						'id'   => $notifiable->getKey(),
						'hash' => sha1($notifiable->getEmailForVerification()),
					]
				),
			]
		);
	}

	/**
	 * Set a callback that should be used when building the notification mail message.
	 *
	 * @param \Closure $callback
	 *
	 * @return void
	 */
	public static function toMailUsing($callback)
	{
		static::$toMailCallback = $callback;
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param mixed $notifiable
	 *
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
		];
	}
}
