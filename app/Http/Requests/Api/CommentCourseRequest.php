<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class CommentCourseRequest extends FormRequest
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
            'comment' => 'required',
            'course_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'comment.required' => trans("validation.review_required"),
            'course_id.required' => trans("validation.course_id_required"),
        ];
    }
}
