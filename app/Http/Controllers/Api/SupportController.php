<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupportController extends Controller
{
	public function sendMessage(Request $request)
	{
		if (empty(auth()->user())) {
			return returnError(401, trans("user_not_found"));
		}
		$validator = Validator::make(
			$request->all(),
			[
				'title' => 'required',
				'message' => 'required',
			],
			[
				'title.required' => trans("validation.title_required"),
				'message.required' => trans("validation.message_required"),
			]
		);
		if ($validator->fails()) {
			return returnError(422, json_decode($validator->errors()->toJson()));
		}
		$chat = Support::create([
			'title' => $request->title,
			'message' => $request->message,
			'user_id' => auth()->user()->id,
		]);
		return returnData('', trans("global.success_send_response"));
	}
}
