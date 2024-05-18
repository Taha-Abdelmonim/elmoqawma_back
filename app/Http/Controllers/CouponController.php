<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Offer;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{


	public function index(Request $request)
	{
		$this->authorize('show_coupons');
		$used_coupons = $request->has('used_coupons');
//		$coursesIDS = Course::Courses()->get()->pluck("id");
//		$courses = Coupon::orderBy('id', 'DESC')->get();
//		$offers = Coupon::orderBy('id', 'DESC')->get();
//		$coupons = $courses->merge($offers); // Contains foo and bar.
		$coupons = Coupon::latest()->get();
		if ($used_coupons) {
			$coupons = Coupon::onlyTrashed()->get();
		}
		return view('coupons.index', compact("coupons", 'used_coupons'));
	}


	public function create()
	{
		$this->authorize('create_coupons');
		$courses = Course::latest()->get();
		return view('coupons.create', compact("courses"));
	}

	public function store(CouponRequest $request)
	{
		//		$code = Str::random(15);
		$this->authorize('create_coupons');
		for ($x = 1; $x <= $request->number; $x++) {
			$coupon = Coupon::create([
				"code" => $request->code . Str::random(15),
				"discount" => $request->discount,
				"type" => $request->type,
				"lecture_id" => $request->lecture_id ?? null,
			]);
		}
		$description = " تم إنشاء كوبون #" . $coupon->code;
		transaction("coupons", $description);
		return redirect()->route('coupons')->with('success', trans("global.success_create"));
	}

	public function show($id)
	{
		$this->authorize('show_coupons');
	}

	public function edit($id)
	{
		$this->authorize('edit_coupons');
		$coupon = Coupon::find($id);
		return view('coupons.edit', compact("coupon"));
	}

	public function update($id, Request $request)
	{
		$this->authorize('edit_coupons');
		$data = $request->all();
		$coupon = Coupon::find($id);
		$coupon->update($data);
		$description = " تم تعديل كوبون #" . $coupon->code;
		transaction("coupons", $description);
		session()->put('success', trans("global.success_create"));
		return redirect()->route('coupons');
	}

	public function destroy($id)
	{
		$this->authorize('remove_coupons');
		$coupon = Coupon::findorFail($id);
		$description = " تم حزف كوبون #" . $coupon->code;
		transaction("coupons", $description);
		$coupon->delete();
		session()->put('success', trans("global.success_delete"));
		return redirect()->route('coupons');
	}

	public function destroyAll(Request $request)
	{

		$this->authorize('remove_coupons');
		$coupons = Coupon::whereIn('id', json_decode($request->ids))->get();
		$description = " تم حزف وبونات (";
		foreach ($coupons as $coupon) {
			$description .= ", " . $coupon->code . " ";
			$coupon->delete();
		}
		$description .= " )";
		transaction("coupons", $description);
		session()->put('success', trans("global.success_delete_all"));
		return redirect()->route("coupons");
	}
}
