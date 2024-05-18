<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use App\Models\Course;
use App\Models\Offer;
use App\Models\Currency;
use App\Models\User;
use App\Notifications\CreateOffer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OfferController extends Controller
{
	public function getOffers(Request $request)
	{
		$offers = Offer::orderBy('id', 'DESC')->get();
		return response(['data' => $offers]);
	}

	public function index()
	{
		$this->authorize('show_offers');
		return view('offers.offers');
	}

	public function create()
	{
		$this->authorize('create_offers');
		$currencies = Currency::orderBy('id', 'ASC')->get();
		$courses = Course::orderBy('id', 'ASC')->get();
		return view('offers.create', compact("currencies", "courses"));
	}


	public function store(OfferRequest $request)
	{
//    dd($request);
		$this->authorize('create_offers');
		$this->validate(
			$request,
			['photo' => 'mimes:jpeg,jpg,png,gif,webp,svg|required'],
			['photo.required' => trans("validation.photo_required"), 'photo.mimes' => trans("validation.photo_mimes")]
		);
		if ($request->file('photo')) {
			$photo = $request->file('photo')->store("offers", 'public_image');
		}

		$data = $request->all();
		$data["photo"] = $photo;
		$offer = Offer::create($data);
		$offer->courses()->syncWithoutDetaching($request->course_id);
		$users = User::where('id', '!=', auth()->user()->id)->get();
		Notification::send($users, new CreateOffer($offer));
		$description = " تم إنشاء عرض " . $request->name_ar;
		transaction("offers", $description);
		session()->put('success', trans("global.success_create"));
		return redirect()->route('offers');
	}

	public function show($id, Request $request)
	{
		//
	}

	public function edit($id)
	{
		$this->authorize('edit_offers');
		$offer = Offer::findorFail($id);
		$currencies = Currency::orderBy('id', 'ASC')->get();
		$courses = Course::orderBy('id', 'ASC')->get();
		$oldCourses = $offer->courses();

		return view('offers.edit', compact('offer', "currencies", "courses", "oldCourses"));
	}

	public function update($id, OfferRequest $request)
	{

		$this->authorize('edit_offers');
		$offer = Offer::findorFail($id);
		$photo = $offer->photo;
		if ($request->file('photo')) {
			$oldPath = public_path("/images/$offer->photo");
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$photo = $request->file('photo')->store("offers", 'public_image');
		}
		$data = $request->all();
		$data["photo"] = $photo;
		$offer->update($data);
		$offer->courses()->sync($request->course_id);
		$description = " تم تعديل عرض " . $offer->name_ar;
		transaction("offers", $description);
		session()->put('success', trans("global.success_update"));
		return redirect()->route('offers');
	}

	public function destroy($id)
	{
		$this->authorize('remove_offers');
		$offer = Offer::findorFail($id);
		$oldPath = public_path("/images/$offer->photo");
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		$offer->courses()->detach();
   DB::table('notifications')->where('data->offer->id', $offer->id)->delete();
		$description = " تم حزف عرض " . $offer->name_ar;
		transaction("offers", $description);
		$offer->delete();
		return redirect()->route('offers')->with('success', trans("global.success_delete"));
	}
}
