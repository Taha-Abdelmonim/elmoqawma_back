<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SectionRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		//      ->whereNull('deleted_at')
		return [
			"name_ar" => [
				"required",
				"max:100",
				Rule::unique('sections')->ignore($this->id),
			],
			"name_en" => [
				"required",
				"max:100",
				Rule::unique('sections')->ignore($this->id),
			],
			"description_ar" => "max:1000|required",
			"description_en" => "max:1000|required",
			//			"section_id" => "required",
			'photo' => 'mimes:jpeg,jpg,png,gif,webp,svg',

		];
	}

	public function messages()
	{

		return [
			"name_ar.unique" => trans("validation.name_ar_unique"),
			"name_ar.required" => trans("validation.name_ar_required"),
			"name_ar.max" => trans("validation.name_ar_max"),
			"name_en.required" => trans("validation.name_en_required"),
			"name_en.unique" => trans("validation.name_en_unique"),
			"name_en.max" => trans("validation.name_en_max"),
			"description_ar.required" => trans("validation.description_ar_required"),
			"description_ar.max" => trans("validation.description_ar_max"),
			"description_en.required" => trans("validation.description_en_required"),
			"description_en.max" => trans("validation.description_en_max"),
			//			"section_id.required" => trans("validation.section_id_required"),
//			'photo.required' => trans("validation.photo_required"),
			'photo.mimes' => trans("validation.photo_mimes")
		];
	}
}
