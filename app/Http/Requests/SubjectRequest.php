<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubjectRequest extends FormRequest
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
				Rule::unique('subjects')->ignore($this->id)->whereNull('deleted_at'),
			],
			"name_en" => [
				"max:100",
				"required",
				Rule::unique('subjects')->ignore($this->id)->whereNull('deleted_at'),
			],
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
		];
	}
}
