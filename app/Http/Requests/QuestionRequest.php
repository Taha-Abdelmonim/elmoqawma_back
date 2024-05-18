<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuestionRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'question_ar' => 'nullable',
			'question_en' => 'nullable',
//			'Justify' => 'required',
//			'related' => 'required',
			'file' => ['nullable', 'file'],
			'type' => ['required', Rule::in(['audio', 'video', 'image', 'text'])],
		];
	}

	public function messages()
	{
		return [
			"question_ar.required" => trans("validation.question_ar_required"),
			"question_en.required" => trans("validation.question_en_required"),
//			"Justify.required" => trans("validation.Justify_required"),
//			"related.required" => trans("validation.related_required"),

		];
	}
}
