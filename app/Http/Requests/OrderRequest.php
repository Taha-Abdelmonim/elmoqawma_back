<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'user_id' => 'required',
			'price' => 'required',
			'card_type' => 'required',
			'currency_id' => 'required',
			'status' => 'required',
			'photo' => 'mimes:jpeg,jpg,png,gif,webp,svg',
		];
	}

	public function messages()
	{
		return [
			'user_id.required' => trans("validation.user_id_required"),
			'price.required' => trans("validation.price_required"),
			'card_type.required' => trans("validation.card_type_required"),
			'currency_id.required' => trans("validation.currency_id_required"),
			'status.required' => trans("validation.status_required"),
			'photo.mimes' => trans("validation.photo_mimes"),
//      'photo.required' => trans("validation.photo_required"),
		];
	}
}
