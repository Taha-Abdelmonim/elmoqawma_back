<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{


	public function handle(Request $request, Closure $next)
	{
		// available language in template array
		$availLocale = ['en' => 'en', 'ar' => 'ar'];
		// Locale is enabled and allowed to be change
		if ($request->has('locale') && array_key_exists($request->get('locale'), $availLocale)) {
			session()->put("locale", $request->get('locale'));
		}
		if (session()->has('locale') && array_key_exists(session()->get('locale'), $availLocale)) {
			app()->setLocale(session()->get('locale'));
		}

//    dd(session()->get('locale'));
//    dd(app()->getLocale());
//    dd(config('app.locale'));
//    dd(trans("global.users"));

		return $next($request);
	}
}
