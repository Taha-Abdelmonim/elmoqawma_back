<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AskRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

//      "name" => "required|max:100|unique:currencies,deleted_at,NULL",
	public function rules()
	{
		return [
			"title_ar" => [
				"required",
				Rule::unique('asks')->ignore($this->id)->whereNull('deleted_at'),
			],
			"title_en" => [
				"required",
				Rule::unique('asks')->ignore($this->id)->whereNull('deleted_at'),
			],
			'description_ar' => 'required',
			'description_en' => 'required',
		];
	}

	public function messages()
	{
		return [
			'title_ar.required' => trans("validation.title_ar_required"),
			"title_ar.unique" => trans("validation.title_ar_unique"),
			'description_ar.required' => trans("validation.description_ar_required"),
			'description_en.required' => trans("validation.description_en_required")
		];
	}
}
