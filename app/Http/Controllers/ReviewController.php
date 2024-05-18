<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use App\Models\Section;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
	public function getReviews()
	{
		$reviews = Review::with(["user", "section"])->orderBy('id', 'DESC')->get();

		return response(['data' => $reviews]);
	}

	public function index()
	{
		$this->authorize('show_reviews');
		return view('reviews.reviews');
	}

	public function toggleStatus($id, Request $request)
	{
		if ($request->ajax() and isset($request->status)) {
			$msg = $request->status == 1 ? trans("global.success_enabled") : trans("global.success_not_enabled");
			$review = Review::findorFail($id);
			$review->update([
				'status' => $request->status,
			]);
			$user = $review->user->name();
			$description = $msg . " التقييم " . " لمستخدم " . $user . " رقم التقييم #" . $review->id;
			transaction("reviews", $description);
			return returnData($review, $msg);
		}
		abort('301', 'unAuthenticated');
	}

	public function create()
	{
		$this->authorize('create_reviews');
		$sections = Section::orderBy('id', 'DESC')->get();
		return view('reviews.create', compact('sections'));
	}

	public function store(ReviewRequest $request)
	{
		$this->authorize('create_reviews');

		$review = Review::create([
			'comment' => $request->comment,
			'status' => auth()->user()->status,
			'section_id' => $request->section_id,
			'user_id' => auth()->user()->id,
		]);
		$user = $review->user->name();
		$description = " تم إنشاء تقييم لمستخدم " . $user . " رقم التقييم #" . $review->id;
		transaction("reviews", $description);
		session()->put('success', trans("global.success_create"));
		return redirect()->route('reviews');
	}

	public function show($id)
	{
		$this->authorize('show_reviews');
		$review = Review::findorFail($id);
		return view('reviews.show', compact('review'));
	}

	public function edit(Review $coment)
	{
		$this->authorize('edit_reviews');
	}

	public function update(Request $request, Review $coment)
	{
		$this->authorize('edit_reviews');
	}

	public function destroy($id)
	{
		$this->authorize('remove_reviews');
		$review = Review::findorFail($id);
		$user = $review->user->name();
		$description = " تم حزف تقييم لمستخدم " . $user . " رقم التقييم #" . $review->id;
		transaction("reviews", $description);
		$review->delete();
		session()->put('success', trans("global.success_delete"));
		return redirect()->route('reviews');
	}
}
