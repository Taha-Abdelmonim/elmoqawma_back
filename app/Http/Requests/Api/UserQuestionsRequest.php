<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class UserQuestionsRequest extends FormRequest
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
			'course_id' => 'required', 'lecture_id' => 'required'
		];
	}

	public function messages()
	{
		return [
			'course_id.required' => trans('validation.course_id_required'),
			'lecture_id.required' => trans('validation.lecture_id_required'),
		];
	}
}
