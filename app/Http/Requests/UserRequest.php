<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{

	public function authorize()
	{
		return true;
	}


	public function rules()
	{
		return [
			'name_ar' => 'required',
			'name_en' => 'required',
			'phone' => 'required|numeric',
			'phone_parent' => 'required|numeric',
			'balance' => 'required',
			"email" => [
				"required",
				"email",
				Rule::unique('users')->ignore($this->id)->whereNull('deleted_at'),
			],
//          'password' => 'required',
			'status' => 'required'
		];
	}

	public function messages()
	{
		return [
			'name_ar.required' => trans("validation.name_ar_required"),
			'name_en.required' => trans("validation.name_en_required"),
			'email.required' => trans("validation.email_required"),
			'email.email' => trans("validation.email_valid"),
			'email.unique' => trans("validation.email_unique"),
//        'password.required' => trans("validation.password_required"),
			'status.required' => trans("validation.status_required"),
			'phone.required' => trans("validation.phone_required"),
			'phone_parent.required' => trans("validation.phone_parent_required"),
			'balance.required' => trans("validation.balance_required"),
			'phone.numeric' => trans("validation.phone_numeric"),
			'phone_parent.numeric' => trans("validation.phone_parent_numeric"),
		];
	}
}
