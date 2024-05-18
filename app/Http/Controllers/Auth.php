<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as AuthUser;

class Auth extends Controller
{


	public function login()
	{
		$pageConfigs = ['myLayout' => 'blank', 'myRTLSupport' => false];
		return view("auth.login", ['pageConfigs' => $pageConfigs]);
	}

	public function checkLogin(Request $request)
	{
//		dd($request);
		$this->validate(
			$request,
			['email' => 'required|email', 'password' => 'required|min:6',],
			[
				'password.required' => trans("validation.password_required"),
				'password.min' => trans("validation.password_min"),
				'email.required' => trans("validation.email_required"),
				'email.email' => trans("validation.email_valid"),
			]
		);
		$remember_token = $request->remember_token == "on" ? 1 : false;
		$credentials = ['email' => $request->email, 'password' => $request->password];
		if (auth()->attempt($credentials, $remember_token)) {
			AuthUser::logoutOtherDevices($request->get('password'));
//			auth()->logoutOtherDevices($request->get('password'));
			return redirect("/")->with('success', trans("global.success_login"));
		} else {
			$request->flash();
			return redirect()->route("login")->withInput()->withErrors([
				'email' => 'Error Email or Password',
				'password' => 'Error Email or Password',
			]);
		}
	}

	public function logout(Request $request)
	{
		auth()->logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		$request->session()->put('success', trans("global.success_logout"));
		return redirect()->route("login");
	}
}
