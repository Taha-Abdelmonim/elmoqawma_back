<?php

namespace App\Services\Api;

use App\Models\Coupon;
use App\Models\Course;
use App\Models\Lecture;
use App\Models\LectureUser;
use App\Models\Offer;
use App\Models\Order;


class PayOrder
{
	public $type,
		$request,
		$data,
		$order,
		$orderType,
		$path = "",
		$price,
		$status = 0,
		$user;

	public function __construct($type, $request)
	{
		$this->type = $type;
		$this->data = $request->all();
		$this->user = auth()->guard('api')->user();
		$this->request = $request;
		if ($type === "course") {
			$this->order = Order::where("course_id", $request->ID)->whereNull("lecture_id")->orderBy("id", "DESC")->where("user_id", $this->user->id)->first();
		} elseif ($type === "lecture") {
			$this->order = Order::where("lecture_id", $request->ID)->orderBy("id", "DESC")->where("user_id", $this->user->id)->first();
		} elseif ($type === "offer") {
			$this->order = Order::where("offer_id", $request->ID)->where("user_id", $this->user->id)->orderBy("id", "DESC")->first();
		}
	}

	public function deleteOldOrder()
	{
		if (!empty($this->order)) {
			$oldPath = public_path("/images/" . $this->path);
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$this->order->delete();
			$this->path = "";
		}
	}

	public function cash()
	{
		$update = false;
		if ($this->request->file("photo")) {
			if (!empty($this->order)) {
				$this->path = $this->order->photo;
				$oldPath = public_path("/images/" . $this->path);
				if (is_file($oldPath)) {
					unlink($oldPath);
				}
				$this->path = $this->request->file("photo")->store("orders", "public_image");
				$this->order->photo = $this->path;
				$this->order->save();
				$update = true;
			} else {
				$this->path = $this->request->file("photo")->store("orders", "public_image");
				return $this->save();
			}
		}
		return $update;
	}

	public function wallet()
	{
		if ($this->request->card_type === "wallet") {
			if ($this->user->balance >= $this->price) {
				$this->user->balance -= $this->price;
				$this->user->save();
				$this->status = 1;
			} else {
				return returnError(401, trans("global.balance_gl_money"));
			}
		}
		return $this->save();
	}

	public function code()
	{
		if ($this->type === "lecture") {
			$coupon = Coupon::where("code", $this->request->code)->where("type", $this->type)->where("lecture_id", $this->request->ID)->first();
		} else {
			$coupon = Coupon::where("code", $this->request->code)->where("type", $this->type)->first();
		}
		if (!empty($coupon)) {
			if ($coupon->discount >= $this->orderType->price) {
				if ($this->type === "lecture") {
					LectureUser::create([
						"user_id" => $this->user->id,
						"course_id" => $this->orderType->course_id,
						"lecture_id" => $this->orderType->id,
						"status" => 1,
					]);
				}
				$this->status = 1;
				$this->user->balance += $coupon->discount - $this->price;
				$this->user->save();
			} else {
				return returnError(403, trans("global.invalid_coupon_discount"));
			}
			$type = $this->type === "lecture" ? "course_id" : $this->type . "_id";
			$ID = $this->type === "lecture" ? $this->orderType->course_id : $this->orderType->id;
			$coupon->$type = $ID;
			$coupon->user_id = $this->user->id;
			$coupon->save();
			$coupon->delete();
		} else {
			return returnError(403, trans("global.invalid_coupon"));
		}
		return $this->save();
	}


	public function orderType()
	{
		if ($this->type === "course") {
			$this->orderType = Course::find($this->request->ID);
		} elseif ($this->type === "lecture") {
			$this->orderType = Lecture::find($this->request->ID);
			$this->orderType->currency_id = $this->orderType->course->currency_id;
			$this->data["course_id"] = $this->orderType->course_id;
		} elseif ($this->type === "offer") {
			$this->orderType = Offer::find($this->request->ID);
		}
		$this->price = $this->orderType->price;
		$method = $this->request->card_type;
		return $this->$method();
	}

	public function save()
	{
		$this->data["photo"] = $this->path;
		$this->data["user_id"] = $this->user->id;
		$this->data[$this->type . "_id"] = $this->orderType->id;
		$this->data["type"] = $this->type;
		$this->data["price"] = $this->orderType->price;
		$this->data["card_type"] = $this->request->card_type;
		$this->data["currency_id"] = $this->orderType->currency_id;
		$this->data["status"] = $this->status;
		$this->deleteOldOrder();
		$this->order = Order::create($this->data);
		$this->saveTransaction();
		return returnData(["order" => $this->order, "balance" => $this->user->balance], trans("global.active_order"));
	}

	public function saveTransaction()
	{
		$description = " تم إنشاء إشتراك من الفرونت لمستخدم " . $this->user["name_" . app()->getLocale()] . " رقم الاشتراك #" . $this->order->id;
		transaction("course_orders", $description);
	}

	public function pay()
	{
		if (!empty($this->order) && $this->request->card_type === "cash" && $this->cash()) {
			return returnData(["order" => $this->order, "user" => $this->user], trans("global.wait_active_order"));
		}
		return $this->orderType();
	}

}
