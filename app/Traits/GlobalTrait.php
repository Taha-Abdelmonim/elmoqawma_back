<?php

namespace App\Traits;

use ToshY\BunnyNet\Client\BunnyClient;
use ToshY\BunnyNet\StreamAPI;
use MacsiDigital\Zoom\Facades\Zoom;

trait GlobalTrait
{
	/*public static function send_whatsapp_massage($message, $phone, $temp, $sub_massage = null)
	{
		$fcmUrl = "https://graph.facebook.com/v15.0/104505749186776/messages";

		$headers = [
			'Authorization: Bearer EAASkCUF4DjUBAKc2drlZCJYIZA8PZBkUvJV3BOQUuZAFZBMTR9TPo74bGupuQXtZB2lnZCfYQxbxOUjOwt5Ez0fdUmnrd7ryrZCoz21TIsFZB5uLBs8LKkBhDIvgQeOzQquzafjeUbySf4zNhUOWqpC3E8471cUyDQ5PBIC0wFs4dVBL5ZBprQNeNC75bZAyjLZC942NFGyFioR5cmZAQuV4FgQykLk2TqZC1oJh8ZD',
			'Content-Type: application/json'
		];

		$data = [
			"messaging_product" => "whatsapp",
			"recipient_type" => "individual",
			"to" => "$phone",
			"type" => "template",
			"template" => [
				"name" => "$temp",
				"language" => [
					"code" => "ar"
				],
				"components" => [
					[
						"type" => "body",
						"parameters" => [
							[
								"type" => "text",
								"text" => "TEXT-STRING"
							]
						]
					],
					[
						"type" => "button",
						"sub_type" => "url",
						"index" => "0",
						"parameters" => [
							[
								"type" => "text",
								"text" => "$sub_massage"
							]
						]
					]
				]

			]
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $fcmUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		$result = curl_exec($ch);

		curl_close($ch);

		return $result;
	}*/


	public function sendMessageWhatsapp()
	{
		$to = '201090770686';
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.facebook.com/v15.0/104505749186776/messages',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
        "messaging_product": "whatsapp",
        "to": ' . $to . ',
        "type": "template",
        "template": {
					"name": "test_otp_2",
					"language": {
						 "code" => "ar"
					},
					"components" => [
						[
							"type" => "body",
							"parameters" => [
								[
									"type" => "text",
									"text" => "TEXT-STRING"
								]
							]
						],
						[
							"type" => "button",
							"sub_type" => "url",
							"index" => "0",
							"parameters" => [
								[
									"type" => "text",
									"text" => "fffffffffsdfd"
								]
							]
						]
					]
        }
    }',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer EAAEaxgQZAWd8BOywpdMGtONrEFWfmXqtZBa75KD6DzUUd3zPaVySbopXFaD7QrySTf6ixFlm5jL0iWr44zsmw6DmLbTZCZByQWdSunbQChfjxww0iyosOdth253ixWLZCqUiLGn4xaW6ZAWy3AdGy4z920Qik21PxxBoYYvZA4iCRFWUY38P7mBdXTIgpdBilUCmWcGc9G6OQyD9tc9ZBeYZD',
				'Content-Type: application/json'
			),
		));
//test_otp_2
		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}


	public function createMeeting($request)
	{

		$user = Zoom::user()->first();

		$meeting = Zoom::meeting()->make([
			'topic' => $request->title,
			'duration' => $request->duration,
			'start_time' => $request->start_time,
			'type' => 2,
			'timezone' => 'Africa/Cairo',
		]);
//			'timezone' => config('zoom.timezone') 'start_time' => Carbon::now(), 'password' => $request->password,

		$meeting->settings()->make([
			'join_before_host' => false,
			'host_video' => false,
			'participant_video' => false,
			'mute_upon_entry' => true,
			'waiting_room' => true,
			'approval_type' => config('zoom.approval_type'),
			'audio' => config('zoom.audio'),
			'auto_recording' => config('zoom.auto_recording')
		]);

		return $user->meetings()->save($meeting);

	}

	public function connectBunny()
	{
		ini_set('default_socket_timeout', '-1');
		$bunnyClient = new BunnyClient(
			client: new \Symfony\Component\HttpClient\Psr18Client()
		);
		$streamApi = new StreamAPI(
			apiKey: env("BUNNY_API_KEY"),
			client: $bunnyClient
		);
		return $streamApi;
	}

	public function uploadVideo($title, $collectionID, $video)
	{
		$streamApi = $this->connectBunny();
		$content = file_get_contents($video);
		$create = $streamApi->createVideo(
			libraryId: env("BUNNY_LIBRARY_ID"),
			body: [
				'title' => $title,
				'collectionId' => $collectionID,
			],
		)->getContents();
		$upload = $streamApi->uploadVideo(
			libraryId: env("BUNNY_LIBRARY_ID"),
			videoId: $create["guid"],
			body: $content,
			query: [
				'enabledResolutions' => '720p',
			],
		);
		return $create;
	}
}