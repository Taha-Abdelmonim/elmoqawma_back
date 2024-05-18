<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LectureRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{

		return [
//				"required",
			/*"name_ar" => [
				Rule::unique('lectures')->ignore($this->id)->whereNull('deleted_at'),
			],
			"name_en" => [
				Rule::unique('lectures')->ignore($this->id)->whereNull('deleted_at'),
			],*/
			'title' => 'required',
			'type' => 'required',
			'order' => 'required',
			'lecture_views' => 'required',
			'type_video' => 'required',
			'price' => 'required',
			're_exam_count' => 'required',
			'duration_exam' => 'required',
			'count_questions' => 'required',
			'status' => 'required',
		];
	}

	public function messages()
	{
		return [

			'title.required' => trans("validation.title_required"),
			'order.required' => trans("validation.order_required"),
			'type.required' => trans("validation.type_required"),
			'type_video.required' => trans("validation.type_video_required"),
			'price.required' => trans("validation.price_required"),
			're_exam_count.required' => trans("validation.re_exam_count_required"),
			"duration_exam.required" => trans("validation.duration_required"),
			'count_questions.required' => trans("validation.count_questions_required"),
			'status.required' => trans("validation.status_required"),
			'lecture_views.required' => trans("validation.lecture_views_required"),
		];
	}
}
