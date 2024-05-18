<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseDetailsRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      "description" => "required",
      "expense_id" => "required",
      "amount" => "required",
     
    ];
  }
  public function messages()
  {
    return [
      "description.required" => trans("validation.description_required"),
      "expense_id.required" => trans("validation.expense_id_required"),
      "amount.required" => trans("validation.amount_required"),
    ];
  }
}
