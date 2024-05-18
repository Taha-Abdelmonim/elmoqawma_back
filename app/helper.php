<?php

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

//  check user Permission
function check($permission)
{
	return auth()->user()->hasPermissionTo($permission);
}

function currencyEGP($models, $input = "amount")
{
	$total = 0;
	foreach ($models as $model) {
		$currency = $model->currency->currency_rate;
		$total += $currency * $model[$input];
	}
	return $total;
}

function transaction($db_name, $description)
{
	Transaction::create([
		"db_name" => $db_name,
		"description" => $description,
		"user_id" => auth()->user()->id,
	]);
}

function getUsersRole($role)
{
	$users = User::orderBy("id", "DESC")->get();
	foreach ($users as $key => $user) {
		if (!$user->hasRole($role)) {
			unset($users[$key]);
		}
	}
	return $users;
}

function getIdSection($name)
{
	$sections = App\Models\Section::select("slug", "id")->get()->toArray();
	$idSection = null;
	for ($i = 0; $i < count($sections); $i++) {
		if (json_decode($sections[$i]["slug"]) != null) {
			if (json_decode($sections[$i]["slug"])[0] == $name) {
				$idSection = $sections[$i]["id"];
			}
		}
	}
	return $idSection;
}

function assignValue(Illuminate\Http\Request $request, $value_1, $value_2, $name_filed)
{
	if (empty($request[$value_1]) and empty($request[$value_2])) {
		if (app()->getLocale() == 'ar') {
			return redirect()->back()->withInput()->withErrors([
				$value_1 => "حقل $name_filed عربي و إنجليزي يجب ادخال قيمة واحدة علا الاقل",
				$value_2 => "حقل $name_filed عربي و إنجليزي يجب ادخال قيمة واحدة علا الاقل"
			]);
		}
		return redirect()->back()->withInput()->withErrors([
			$value_1 => "$name_filed field Arabic and English You must enter at least one value",
			$value_2 => "$name_filed field Arabic and English You must enter at least one value",
		]);
	} else if (empty($request[$value_1]) and !empty($request[$value_2])) {
		return $request[$value_1] = $request[$value_2];
	} else if (!empty($request[$value_1]) and empty($request[$value_2])) {
		return $request[$value_2] = $request[$value_1];
	}
	return "true";
}

function returnData($value, $message = '')
{
	return response()->json([
		'status' => true,
		'message' => $message,
		'data' => $value,
	]);
}

function returnError($errCode, $msg)
{
	return response()->json([
		'status' => false,
		'errCode' => $errCode,
		'message' => $msg,
	]);
}

function checkSavePhoto($file, $photo, $folder)
{
	if ($file) {
		Storage::disk('public_image')->delete($photo);
		$photo = $file->store($folder, "public_image");
	}
	return $photo;
}

function LocalKey($model, $key)
{
	return $model["{$key}_" . app()->getLocale()];
}

function deleteOldItems($request, $model)
{
	if ($request->delete_items != "[]") {
		$model = "App\Models\\$model";
		$model::whereIn("id", json_decode($request->delete_items))->delete();
	}
}

function addKeyValue($array, $key, $value)
{
	foreach ($array as &$item) $item[$key] = $value;
	return $array;
}

function DateValue($date)
{
	return Carbon::parse($date)->format('Y/m/d h:i:s A');
}

function convertMinutes($minutes)
{
	$duration = Carbon::now()->addMinutes(++$minutes);
	$hours = $duration->diffInHours(Carbon::now());
	$remainingMinutes = $duration->diffInMinutes(Carbon::now()) % 60;

	return "المدة الزمنية: $hours ساعات و $remainingMinutes دقائق";

}