<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ask;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\LectureUser;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\Api\PayOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class AppController extends Controller
{
	public function readNotification($id)
	{
		DB::table('notifications')->where('id', $id)->update(['read_at' => now()]);
		return returnData("", "تم قرائة الإشعار");
	}

	public function app(Request $request)
	{
		try {
			//			notifications and User
			$user = auth()->guard('api')->user() ?? new User();
			$user->subscribed = 0;
			if ($user !== null) {
				$user->unreadNotifications;
				$user->access_token = $request->bearerToken();
				$user->subscribed = (bool)Order::where("status", 1)->where("user_id", $user->id)->first();
			}

			//			settings
			$settings = Setting::all();
			$filterSettings = [];
			foreach ($settings as $value) {
				if ($value->photo !== null) {
					$filterSettings[$value->key] = $value->photo;
				} else {
					$filterSettings[$value->key] = $value["value_" . app()->getLocale()];
				}
			}
			return returnData(["settings" => $filterSettings, "user" => $user]);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError("", trans("global.any_mistake"));
		}
	}


	public function getAsks()
	{
		try {
			$asks = Ask::all();
			return returnData($asks);
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function walletPay($code)
	{
		try {

//			return response()->json("dffd");
			$user = auth()->guard('api')->user();
			if ($user === null) {
				return returnError(401, trans("global.user_not_found"));
			}
			$coupon = Coupon::where("code", $code)->where("type", "wallet")->first();
			$value = 0;
			if ($coupon != null) {
				$value = $coupon->discount;
				$user->balance += $coupon->discount;
				$user->save();
				$coupon->user_id = $user->id;
				$coupon->save();
				$coupon->delete();
			} else {
				return returnError(403, trans("global.invalid_coupon"));
			}
			return returnData($user, "تم شحن المحفظة بقيمة " . $value . " جنية ");
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}

	public function payBank($type, Request $request)
	{
		try {
			$user = auth()->guard('api')->user();
			$validator = Validator::make($request->all(), ["ID" => "required"], ["ID.required" => trans("validation.ID_required")]);
			if ($validator->fails()) {
				return returnError(422, json_decode($validator->errors()->toJson()));
			}
			if ($user === null) {
				return returnError(401, trans("global.user_not_found"));
			}
			return (new PayOrder($type, $request))->pay();
		} catch (\Exception $ex) {
			return returnError($ex->getCode(), $ex->getMessage());
		}
	}
}
