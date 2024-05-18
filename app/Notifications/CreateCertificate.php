<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateCertificate extends Notification
{
	use Queueable;

	protected $certificate;

	public function __construct($certificate)
	{
		$this->certificate = $certificate;
	}

	public function via($notifiable)
	{
		return ['database'];
	}

	public function toArray($notifiable)
	{
		return [
			'certificate' => $this->certificate,
		];
	}
}
