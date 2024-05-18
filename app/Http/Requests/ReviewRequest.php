<?php

  namespace App\Http\Requests;

  use Illuminate\Foundation\Http\FormRequest;
  use Illuminate\Validation\Rule;
  
  class ReviewRequest extends FormRequest
  {

    public function authorize()
    {
      return true;
    }


    public function rules()
    {
      return [
        'comment' => 'required',
        'status' => 'required',
        'section_id' => 'required',
      ];
    }
    public function messages()
    {
      return [
        'comment.required' => trans("validation.review_required"),
        'status.required' => trans("validation.status_required"),
        'section_id.required' => trans("validation.section_required"),
      ];
    }
  }
