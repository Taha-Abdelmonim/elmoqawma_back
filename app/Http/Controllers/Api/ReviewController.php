<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
	

	public function sendReview(Request $request)
	{
		if (empty(auth()->user())) {
			return returnError(401, trans("user_not_found"));
		}
		$validator = Validator::make(
			$request->all(),
			[
				'comment' => 'required',
				'section_id' => 'required',
			],
			[
				'comment.required' => trans('validation.review_required'),
				'section_id.required' => trans('validation.section_required'),
			]
		);
		if ($validator->fails()) {
			return returnError(422, json_decode($validator->errors()->toJson()));
		}
		$chat = Review::create([
			'comment' => $request->comment,
			'section_id' => $request->section_id,
			'user_id' => auth()->user()->id,
			'status' => 0,
		]);
		return returnData('', trans("global.success_send_response"));
	}
}
