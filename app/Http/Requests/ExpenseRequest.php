<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      "name_ar" => [
        "max:100",
        "required",
        Rule::unique('expenses')->ignore($this->id)->whereNull('deleted_at'),
      ],
      "name_en" => [
        "max:100",
        "required",
        Rule::unique('expenses')->ignore($this->id)->whereNull('deleted_at'),
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
