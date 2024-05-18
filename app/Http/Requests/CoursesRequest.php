<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CoursesRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'subject_id' => 'required',
			'section_id' => 'required',
//			'teacher_id' => 'required',
			'price' => 'required|numeric',
			'sub_price' => 'required|numeric',
			'discount' => 'required|numeric',
			'whatsapp' => 'required',
			'telegram' => 'required',
			'subscribers' => 'required|numeric',
			'subscription_duration' => 'nullable|integer',
			'status' => 'required',

			'description_ar' => 'required',
			'description_en' => 'required',
			//"photo" => "mimes:jpeg,jpg,png,gif,webp,svg|required"
		];
	}

	public function messages()
	{
		return [
			'subject_id.required' => trans("validation.subject_id_required"),
			'section_id.required' => trans("validation.section_required"),
			'currency_id.required' => trans("validation.currency_id_required"),
//			'teacher_id.required' => trans("validation.coach_required"),


			'price.required' => trans("validation.price_required"),
			'price.numeric' => trans("validation.price_numeric"),

			'sub_price.required' => trans("validation.sub_price_required"),
			'sub_price.numeric' => trans("validation.sub_price_numeric"),

			'discount.required' => trans("validation.discount_required"),
			'discount.numeric' => trans("validation.discount_numeric"),


			'whatsapp.required' => trans("validation.whatsapp_required"),
			'telegram.required' => trans("validation.telegram_required"),


			'subscribers.required' => trans("validation.subscribers_required"),
			'subscribers.numeric' => trans("validation.subscribers_numeric"),


			'text_date.required' => trans("validation.text_date_required"),
			'text_time.required' => trans("validation.text_time_required"),
			//'photo.required' => trans("validation.photo_required"),
			//'photo.mimes' => trans("validation.photo_mimes"),

			'status.required' => trans("validation.status_required"),


			'description_ar.required' => trans("validation.description_ar_required"),
			'description_en.required' => trans("validation.description_en_required"),


		];
	}
}
