<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserExamRequest extends FormRequest
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
			'course_id' => 'required',
			'lecture_id' => 'required',
			'score' => 'required',
			'correct' => 'required',
			'mistake' => 'required',
			'latest' => 'required',
//			'answers' => 'required',
		];
	}

	public function messages()
	{
		return [
			'course_id.required' => trans('validation.course_id_required'),
			'lecture_id.required' => trans('validation.lecture_id_required'),
			'score.required' => trans('validation.score_required'),
			'correct.required' => trans('validation.correct_required'),
			'mistake.required' => trans('validation.mistake_required'),
			'latest.required' => trans('validation.latest_required'),
//			'answers.required' => trans('validation.answers_required'),
		];
	}
}
