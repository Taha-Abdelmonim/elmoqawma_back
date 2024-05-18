<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }
//      "name" => "required|max:100|unique:currencies,deleted_at,NULL",
  public function rules()
  {
    return [
      "name" => [
        "required",
        "max:100",
          Rule::unique('currencies')->ignore($this->id)->whereNull('deleted_at'),
      ],
      "currency_symbol" => [
        "required",
        "max:10",
        Rule::unique('currencies')->ignore($this->id)->whereNull('deleted_at'),
      ],
      "currency_rate" => ["numeric", "required"],
    ];
  }
  public function messages()
  {
    return [
      "name.required" => trans("validation.required_name"),
      "name.unique" => trans("validation.unique_name"),
      "name.max" => trans("validation.max_name"),
      "currency_symbol.required" => trans("validation.required_symbol"),
      "currency_symbol.max" => trans("validation.max_symbol"),
      "currency_symbol.unique" => trans("validation.unique_symbol"),
      "currency_rate.numeric" => trans("validation.numeric_rate"),
      "currency_rate.required" => trans("validation.required_rate"),
    ];
  }
}
