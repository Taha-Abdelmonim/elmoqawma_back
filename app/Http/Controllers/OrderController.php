<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\AmountBank;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Currency;
use App\Models\Lecture;
use App\Models\Offer;
use App\Models\Order;
use App\Models\User;
use App\Notifications\CreateCourse;
use App\Notifications\CreateOffer;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
	public function toggleStatus($id, Request $request)
	{

		$this->authorize('order_toggle_status');
		if ($request->ajax() and isset($request->status)) {
			$msg = $request->status == 1 ? trans("global.success_enabled") : trans("global.success_not_enabled");
			$order = Order::findorFail($id);
			$order->update([
				'status' => $request->status,
			]);
			if ($order->type === 'course' || $order->type === 'lecture') {
				$course = $order->course;
				$course->subject;
				$course->message = $msg . " " . trans('global.' . $order->type);
				Notification::send($order->user, new CreateCourse($course));
			} else if ($order->type === 'offer') {
				$offer = $order->offer;
				$offer->message = $msg . " العرض ";
				Notification::send($order->user, new CreateOffer($offer));
			}
			$amountBank = AmountBank::where("bank_id", 1)->where("currency_id", $order->currency_id)->first();
			if ($request->status == 1) {
				$amountBank->amount = $amountBank->amount + $order->price;
			} else {
				$amountBank->amount = $amountBank->amount - $order->price;
			}
			$amountBank->save();
			$user = $order->user->name();
			$description = $msg . " اﻹشتراك " . " لمستخدم " . $user . " رقم الاشتراك #" . $order->id;
			transaction("orders", $description);
			return returnData($order, $msg);
		}
		abort('301', 'unAuthenticated');
	}

	public function ordersCourses()
	{

		$this->authorize('show_orders');
		$courses = Course::Courses()->get();

		foreach ($courses as $course) {
			$ordersCourse = $course->getOrdersCourse($course->id);
			$course->active = $ordersCourse->where("status", 1)->count();
			$course->inactive = $ordersCourse->where("status", 0)->count();
		}
		return view('orders.orders_courses', compact("courses"));
	}


	public function coursesLectures()
	{
		$this->authorize('show_orders');
		$courses = Course::Courses()->get();
		foreach ($courses as $course) {
			$course->lectures_count = Lecture::where("course_id", $course->id)->get()->count();
		}
		return view('orders.courses', compact("courses"));
	}

	public function courseLectures($courseID)
	{
		$this->authorize('show_orders');
//		$course = Course::find($courseID);
		$lectures = Lecture::where("course_id", $courseID)->orderBy('id', 'DESC')->get();
		foreach ($lectures as $lecture) {
			$ordersLecture = Order::where("course_id", $courseID)->where("lecture_id", $lecture->id)->orderBy('id', 'DESC')->get();
			$lecture->active = $ordersLecture->where("status", 1)->count();
			$lecture->inactive = $ordersLecture->where("status", 0)->count();
		}
		return view('orders.orders_lectures', compact("lectures", "courseID"));
	}

	public function getLecture($courseID)
	{
		$lectures = Lecture::where("course_id", $courseID)->orderBy('id', 'DESC')->get();
		return response(view("orders.lectures_ajax", compact("lectures")));
	}

	public function ordersOffers()
	{
		$this->authorize('show_orders');
		$offers = Offer::orderBy('id', 'DESC')->get();
		foreach ($offers as $offer) {
			$ordersOffer = Order::where("offer_id", $offer->id)->orderBy('id', 'DESC')->get();
			$offer->active = $ordersOffer->where("status", 1)->count();
			$offer->inactive = $ordersOffer->where("status", 0)->count();
		}
		return view('orders.orders_offers', compact("offers"));
	}

	public function index($type, Request $request)
	{
		$this->authorize('show_orders');
		if ($type === 'course') {
			$orders = Order::Orders()->whereNotNull("course_id")->whereNull("lecture_id")->orderBy('id', 'DESC')->get();
		} else if ($type === 'lecture') {
			$orders = Order::Orders()->whereNotNull("lecture_id")->orderBy('id', 'DESC')->get();
		} else if ($type === 'offer') {
			$orders = Order::whereNotNull("offer_id")->orderBy('id', 'DESC')->get();
		}
//		dd($orders[0]->lecture->course->name);
		if ($request->has("ID") and $request->has("status")) {
			if ($type === 'course') {
				$orders = Order::Orders()->where($type . "_id", $request->ID)->whereNull("lecture_id")->where("status", $request->status)->orderBy('id', 'DESC')->get();
			} else {
				$orders = Order::Orders()->where($type . "_id", $request->ID)->where("status", $request->status)->orderBy('id', 'DESC')->get();
			}
		}
		return view('orders.orders', compact("orders", "type"));
	}

	public function create($type)
	{

		$this->authorize('create_orders');
		$users = User::orderBy('id', 'DESC')->get();
		$courses = Course::Courses()->get();
		$offers = Offer::orderBy('id', 'DESC')->get();
		$currencies = Currency::orderBy('id', 'ASC')->get();
		$select = $type === "lecture" ? 'course' : $type;
		$coupons = Coupon::whereNotNull($select . "_id")->orderBy('id', 'DESC')->get();
		return view('orders.create', compact('users', "courses", "currencies", "coupons", "offers", "type"));
	}

	public function store($type, OrderRequest $request)
	{
//		dd($request->all());
		$this->authorize('create_orders');
		$path = null;
		if ($request->file('photo')) {
			$path = $request->file('photo')->store('orders', 'public_image');
		}
		$data = $request->all();
		$data["photo"] = $path;
		$data["type"] = $type;

		$order = Order::create($data);
		$user = User::find($request->user_id);
		if ($request->card_type === 'wallet') {
			if ($user->balance >= $request->price) {
				$user->balance -= $request->price;
				$user->save();
			} else {
				return redirect()->back()->with('error', trans("global.balance_gl_money"));
			}
		}
		$amountBank = AmountBank::where("bank_id", 1)->where("currency_id", $request->currency_id)->first();
		if ($request->status == 1) {
			$amountBank->amount = $amountBank->amount + $request->price;
			$amountBank->save();
		}

		$user = $order->user->name();
		$description = " تم إنشاء إشتراك لمستخدم " . $user . " رقم الاشتراك #" . $order->id;
		transaction("orders", $description);
		return redirect()->route('orders', $type)->with('success', trans("global.success_create"));
	}


	public function show($id, Request $request)
	{
		//
	}

	public function edit($type, $id)
	{
		$this->authorize('edit_orders');
		$order = Order::findorFail($id);
		$users = User::orderBy('id', 'DESC')->get();
		$courses = Course::Courses()->get();
		$offers = Offer::orderBy('id', 'DESC')->get();
		$lectures = Lecture::orderBy('id', 'DESC')->get();
		$currencies = Currency::orderBy('id', 'ASC')->get();
		$select = $type === "lecture" ? 'course' : $type;
		$coupons = Coupon::whereNotNull($select . "_id")->orderBy('id', 'DESC')->get();
		return view('orders.edit', compact('order', 'users', "courses", "offers", "lectures", "currencies", "coupons", "type"));
	}

	public function update($type, $id, OrderRequest $request)
	{
		$this->authorize('edit_orders');
		$order = Order::findorFail($id);
		$path = $order->photo;
		if ($request->file('photo')) {
			$oldPath = public_path('/images/' . $order->photo);
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$path = $request->file('photo')->store('orders', 'public_image');
		}
		$user = User::find($request->user_id);
		if ($order->card_type === 'wallet' and $request->card_type === 'wallet') {
			$user->balance += $order->price;
			if ($user->balance >= $request->price) {
				$user->balance -= $request->price;
				$user->save();
			} else {
				return redirect()->back()->with('error', trans("global.balance_gl_money"));
			}
		}
		$amountBankOld = AmountBank::where("bank_id", 1)->where("currency_id", $order->currency_id)->first();
		$amountBankOld->amount -= $order->price;
		$amountBankOld->save();
		$data = $request->all();
		$data["photo"] = $path;
		$data["type"] = $type;
		$order->update($data);
//    dd($amountBankOld);
		$amountBankNew = AmountBank::where("bank_id", 1)->where("currency_id", $request->currency_id)->first();
		$amountBankNew->amount += $request->price;
		$amountBankNew->save();

		$user = $order->user->name();
		$description = " تم تعديل إشتراك لمستخدم " . $user . " رقم الاشتراك #" . $order->id;
		transaction("orders", $description);
		return redirect()->route('orders', $type)->with('success', trans("global.success_update"));
	}

	public function destroy($type, $id)
	{
		$this->authorize('remove_orders');
		$order = Order::findorFail($id);
		$amountBank = AmountBank::where("bank_id", 1)->where("currency_id", $order->currency_id)->first();
		$amountBank->amount = $amountBank->amount - $order->price;
		$amountBank->save();
		$oldPath = public_path('/images/' . $order->photo);
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
//    DB::table('notifications')->where('data->order->id', $order->id)->delete();
		$user = $order->user->name();
		$description = " تم حزف اشتراك المستخدم " . $user . " رقم الاشتراك #" . $order->id;
		transaction("orders", $description);
		$order->delete();
		return redirect()->route('orders', $type)->with('success', trans("global.success_delete"));
	}

	public function destroyAll($type, Request $request)
	{
		$this->authorize('remove_orders');
		$orders = Order::whereIn('id', json_decode($request->ids))->get();
		$description = " تم حزف إشتراكات برقم (";
		foreach ($orders as $order) {
			$amountBank = AmountBank::where("bank_id", 1)->where("currency_id", $order->currency_id)->first();
			$amountBank->amount = $amountBank->amount - $order->price;
			$amountBank->save();
			$oldPath = public_path('/images/' . $order->photo);
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
//      DB::table('notifications')->where('data->order->id', $order->id)->delete();
			$description .= ", " . $order->id . " ";
			$order->delete();
		}
		$description .= " )";
		transaction("orders", $description);
		return redirect()->route('orders', $type)->with('success', trans("global.success_delete_all"));
	}
}
