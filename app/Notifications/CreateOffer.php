<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CreateOffer extends Notification
{
	use Queueable;

	protected $offer;

	public function __construct($offer)
	{
		$this->offer = $offer;
	}

	public function via($notifiable)
	{
		return ['database'];
	}

	public function toArray($notifiable)
	{
		return [
			'offer' => $this->offer,
		];
	}
}
