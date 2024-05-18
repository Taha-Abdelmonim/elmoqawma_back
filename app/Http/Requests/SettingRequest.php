<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			"name" => [
				"max:100",
				"required",
				Rule::unique('settings')->ignore($this->id)->whereNull('deleted_at'),
			],
			'key' => 'required',
			'type' => 'required',
//			'value_ar' => 'required',
//			'value_en' => 'required',
			'photo' => 'mimes:jpeg,jpg,png,gif,webp,svg',
		];
	}

	public function messages()
	{
		return [
			"name.unique" => trans("validation.name_unique"),
			"name.required" => trans("validation.name_required"),
			"name.max" => trans("validation.name_max"),
			'key.required' => trans("validation.key_required"),
			'type.required' => trans("validation.type_required"),
//			'value_ar.required' => trans("validation.value_ar_required"),
//			'value_en.required' => trans("validation.value_en_required"),
			'photo.mimes' => trans("validation.photo_mimes")
		];
	}
}
