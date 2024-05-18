<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OfferRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			"name_ar" => [
				"max:100",
				"required",
				Rule::unique('offers')->ignore($this->id)->whereNull('deleted_at'),
			],
			"name_en" => [
				"max:100",
				"required",
				Rule::unique('offers')->ignore($this->id)->whereNull('deleted_at'),
			],

			'price' => 'required|numeric',
			'subscribers' => 'required|numeric',
			'stars' => 'required|numeric',
			'duration' => 'required',
			'description_ar' => 'required',
			'description_en' => 'required',
			'currency_id' => 'required',
			'course_id' => 'required',
		];
	}

	public function messages()
	{
		return [
			"name_ar.unique" => trans("validation.name_ar_unique"),
			"name_ar.required" => trans("validation.name_ar_required"),
			"name_ar.max" => trans("validation.name_ar_max"),

			"name_en.unique" => trans("validation.name_en_unique"),
			"name_en.required" => trans("validation.name_en_required"),
			"name_en.max" => trans("validation.name_en_max"),

			'price.required' => trans("validation.price_required"),
			'price.numeric' => trans("validation.price_numeric"),

			'subscribers.required' => trans("validation.subscribers_required"),
			'subscribers.numeric' => trans("validation.subscribers_numeric"),

			'stars.required' => trans("validation.stars_required"),
			'stars.numeric' => trans("validation.stars_numeric"),

			'duration.required' => trans("validation.duration_required"),

			'description_ar.required' => trans("validation.description_ar_required"),
			'description_en.required' => trans("validation.description_en_required"),

			'currency_id.required' => trans("validation.currency_id_required"),
			'course_id.required' => trans("validation.course_id_required"),
		];
	}
}
