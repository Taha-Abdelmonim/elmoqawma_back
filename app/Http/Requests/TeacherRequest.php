<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [

			"name_ar" => [
				"max:100",
				"required",
				Rule::unique('teachers')->ignore($this->id)->whereNull('deleted_at'),
			],
			"name_en" => [
				"max:100",
				"required",
				Rule::unique('teachers')->ignore($this->id)->whereNull('deleted_at'),
			],

			'description_ar' => 'required',
			'description_en' => 'required',

//			'photo' => 'required|mimes:jpeg,jpg,png,gif,webp,svg',
//			'sections' => 'required',
//			'courses' => 'required',
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
			"email.unique" => trans("validation.email_unique"),
			'description_ar.required' => trans("validation.description_ar_required"),
			'description_en.required' => trans("validation.description_en_required"),
			'sections.required' => trans("validation.sections_required"),
//			'photo.required' => trans("validation.photo_required"),
//			'photo.mimes' => trans("validation.photo_mimes"),
//			'courses.required' => trans("validation.courses_required"),
		];
	}
}
