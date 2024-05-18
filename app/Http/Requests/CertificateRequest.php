<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

//      "name" => "required|max:100|unique:currencies,deleted_at,NULL",
	public function rules()
	{
		return [
			'username' => 'required|string|max:255',
			'status' => 'required|integer|min:0',
			'score' => 'required|numeric|min:0',
			'user_id' => 'required',
		];
	}

	public function messages()
	{
		return [
			'username.required' => trans("validation.username_required"),
			'status.required' => trans("validation.status_required"),
			'score.required' => trans("validation.score_required"),
			'user_id.required' => trans("validation.user_id_required"),
			"username.string" => trans("validation.username_string"),
			"username.max" => trans("validation.username_max_255"),
			"status.integer" => trans("validation.status_integer"),
			"score.numeric" => trans("validation.score_numeric"),
			"status.min" => trans("validation.status_min_0"),
			"score.min" => trans("validation.score_min_0"),
		];
	}
}
