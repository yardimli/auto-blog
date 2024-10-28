<?php

	namespace App\Mail;

	use Illuminate\Bus\Queueable;
	use Illuminate\Contracts\Queue\ShouldQueue;
	use Illuminate\Mail\Mailable;
	use Illuminate\Mail\Mailables\Content;
	use Illuminate\Mail\Mailables\Envelope;
	use Illuminate\Queue\SerializesModels;

	class ResetPasswordMail extends Mailable
	{
		use Queueable, SerializesModels;

		public $token;
		public $email;

		public function __construct($token, $email)
		{
			$this->token = $token;
			$this->email = $email;
		}

		public function build()
		{

			$locale = \App::getLocale() ?: config('app.fallback_locale', 'zh_TW');

			$subject = '【織音】- 密碼重設申請';
			$email_view = 'emails.reset_password_zh_TW';
			if ($locale == 'en_US') {
				$subject = '【Auto Blog】- Password Reset Request';
				$email_view = 'emails.reset_password';
			}
			if ($locale == 'tr_TR') {
				$subject = '【Auto Blog】- Şifre Sıfırlama İsteği';
				$email_view = 'emails.reset_password_tr';
			}

			return $this->from(env('MAIL_FROM_ADDRESS','support@my-laravel-saas-site.com'), env('MAIL_FROM_NAME', 'Auto Blog Support'))
				->subject($subject)
				->view($email_view)
				->with(['token' => $this->token, 'email' => $this->email]);
		}
	}
