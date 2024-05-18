<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
	public function trainingHours(Request $request)
	{
		function getOrders($teacher_id, $from, $to)
		{
			$coursesIDS = Course::whereHas('teacher', function ($query) use ($teacher_id) {
				$query->where('teachers.id', $teacher_id);
			})->when(!empty($from), function ($query) use ($from) {
				return $query->whereDate("created_at", ">=", $from);
			})->when(!empty($to), function ($query) use ($to) {
				return $query->whereDate("created_at", "<=", $to);
			})->pluck("id");
//			->sum("duration")
			$lectures = Lecture::whereIn("course_id", $coursesIDS)->whereNotNull("duration")->get();
			return $lectures;
		}

		$teacher_id = auth()->user()->teacher_id ?? $request->teacher_id;
		$from = $request->from;
		$to = $request->to;
		$lectures = getOrders($teacher_id, $from, $to);
		$teachers = Teacher::orderBy("id", "ASC")->get();
		return view("reports.training_hours", compact('lectures', "teachers"));
	}

	public function teachers(Request $request)
	{
		$this->authorize("reports_teachers");
		function getOrders($teacher_id, $from, $to)
		{
			$GetCourses = Course::where('teacher_id', $teacher_id)->get();
			$courses = Order::where('status', 1)->whereIn('course_id', $GetCourses->pluck("id"))
				->when(!empty($from), function ($query) use ($from) {
					return $query->whereDate("created_at", ">=", $from);
				})->when(!empty($to), function ($query) use ($to) {
					return $query->whereDate("created_at", "<=", $to);
				})->orderBy('id', 'DESC')->get();

			/*$allOffersIDS = $GetCourses->pluck('offers')->flatten()->pluck('id')->unique();
			$offers = Order::where('status', 1)->whereIn("offer_id", $allOffersIDS)
				->when(!empty($from), function ($query) use ($from) {
					return $query->whereDate("created_at", ">=", $from);
				})->when(!empty($to), function ($query) use ($to) {
					return $query->whereDate("created_at", "<=", $to);
				})->orderBy('id', 'DESC')->get();*/

			return $courses;
		}

		$teacher_id = auth()->user()->teacher_id ?? $request->teacher_id;
		$from = $request->from;
		$to = $request->to;
		$orders = getOrders($teacher_id, $from, $to);
		$total = $orders->sum("price") ?? 0;
		$teachers = Teacher::orderBy("id", "ASC")->get();
		$type = "teachers";
		return view("reports.index", compact('orders', "teachers", "type", "total"));
	}

	public function courses(Request $request)
	{
		$this->authorize("reports_courses");
		function getOrders($course_id, $from, $to)
		{
			$courses = Order::where('status', 1)->where('course_id', $course_id)->whereNull("lecture_id")
				->when(!empty($from), function ($query) use ($from) {
					return $query->whereDate("created_at", ">=", $from);
				})->when(!empty($to), function ($query) use ($to) {
					return $query->whereDate("created_at", "<=", $to);
				})->orderBy('id', 'DESC')->get();
			return $courses;
		}

		$course_id = $request->course_id ?? 0;
		$from = $request->from;
		$to = $request->to;
		$orders = getOrders($course_id, $from, $to);
		$total = $orders->sum("price") ?? 0;
		$type = "courses";
		$teacher_id = auth()->user()->teacher_id;
		$courses = Course::orderBy("id", "ASC")->get();
		if ($teacher_id !== null) {
			$courses = Course::where('teacher_id', $teacher_id)->get();
		}
		return view("reports.index", compact('orders', "courses", "type", "total"));
	}

	public function lectures(Request $request)
	{
		$this->authorize("reports_lectures");
		function getOrders($lecture_id, $from, $to)
		{
			$lectures = Order::where('status', 1)->where("lecture_id", $lecture_id)
				->when(!empty($from), function ($query) use ($from) {
					return $query->whereDate("created_at", ">=", $from);
				})->when(!empty($to), function ($query) use ($to) {
					return $query->whereDate("created_at", "<=", $to);
				})->orderBy('id', 'DESC')->get();
			return $lectures;
		}

		$lecture_id = $request->lecture_id ?? 0;
		$from = $request->from;
		$to = $request->to;

		$orders = getOrders($lecture_id, $from, $to);
		$total = $orders->sum("price") ?? 0;
		$type = "lectures";
		$teacher_id = auth()->user()->teacher_id;
		$courses = Course::orderBy("id", "ASC")->get();
		if ($teacher_id !== null) {
			$courses = Course::where('teacher_id', $teacher_id)->get();
		}
		return view("reports.index", compact('orders', "courses", "type", "total"));
	}

	public function offers(Request $request)
	{
		$this->authorize("reports_offers");
		function getOrders($offer_id, $from, $to)
		{
			$offers = Order::where('status', 1)->where("offer_id", $offer_id)
				->when(!empty($from), function ($query) use ($from) {
					return $query->whereDate("created_at", ">=", $from);
				})->when(!empty($to), function ($query) use ($to) {
					return $query->whereDate("created_at", "<=", $to);
				})->orderBy('id', 'DESC')->get();
			return $offers;
		}

		$offer_id = $request->offer_id ?? 0;
		$from = $request->from;
		$to = $request->to;

		$orders = getOrders($offer_id, $from, $to);
		$total = $orders->sum("price") ?? 0;
		$type = "offers";
		$teacher_id = auth()->user()->teacher_id;
		$offers = Offer::orderBy("id", "ASC")->get();
		if ($teacher_id !== null) {
			$courses = Course::where('teacher_id', $teacher_id)->get();
			$allOffersIDS = $courses->pluck('offers')->flatten()->pluck('id')->unique();
			$offers = Offer::whereIn("offer_id", $allOffersIDS)->get();
		}
		return view("reports.index", compact('orders', "offers", "type", "total"));
	}

	//students
	public function students(Request $request)
	{
		$this->authorize("reports_students");
		$user_id = $request->user_id ?? 0;
		$user = User::find($user_id);
		$from = $request->from;
		$to = $request->to;
		$orders = Order::where('status', 1)->where('user_id', $user_id)
			->when(!empty($from), function ($query) use ($from) {
				return $query->whereDate("created_at", ">=", $from);
			})->when(!empty($to), function ($query) use ($to) {
				return $query->whereDate("created_at", "<=", $to);
			})->orderBy('id', 'DESC')->get();
		$total = $orders->sum("price") ?? 0;
		$users = User::orderBy("id", "ASC")->get();
		$type = "students";
		return view("reports.students", compact('orders', "users", "type", "total", "user"));
	}
}
