<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CouponRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

//      "name" => "required|max:100|unique:currencies,deleted_at,NULL",
	public function rules()
	{
		return [
			/*"code" => [
				"required",
				Rule::unique('coupons')->ignore($this->id)->whereNull('deleted_at'),
			],*/
			'discount' => 'required',
			'type' => 'required'
		];
	}

	public function messages()
	{
		return [
			/*'code.required' => trans("validation.code_required"),
			"code.unique" => trans("validation.code_unique"),*/
			'discount.required' => trans("validation.discount_required"),
			'type.required' => trans("validation.type_required")
		];
	}
}
