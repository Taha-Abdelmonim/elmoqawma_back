<?php

namespace App\Services\Api;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\LectureUser;
use App\Models\Order;
use App\Models\Question;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\DB;

class GetCourseService
{
	use GlobalTrait;

	public $course;
	public $user;
	public $userID;
	public $nextLectureID;
	public $CheckQuestionsLectureUser = true;

	public function __construct($courseID)
	{
		$this->course = Course::with(["comments" => ["user"], "section", "teacher", "subject", "currency", "attachments", "chapters.lectures"])->find($courseID);
		if ($this->course) {
			$this->user = auth()->guard('api')->user();
			$this->userID = $this->user ? $this->user->id : 0;
			$this->course->subscribed = $this->checkUserSubscribed();
			$this->course->subscribers += Order::where("status", 1)->where("course_id", $this->course->id)->count();
			$this->course->count_videos = Lecture::where("status", 1)->where("course_id", $this->course->id)->count();
			$this->course->duration_exams = Lecture::where("status", 1)->where("course_id", $this->course->id)->sum("duration_exam");
			$this->course->count_exams = Question::where("course_id", $this->course->id)->groupBy("lecture_id")->count();
			$this->course->count_attachments = $this->course->attachments->count();
		}
	}

	public function checkUserSubscribed(): int
	{
		$order = Order::where("status", 1)->where("course_id", $this->course->id)->whereNull("lecture_id")->where("user_id", $this->userID)->first();
		if (!empty($order)) {
			return 1;
		}
		$offersIDS = Order::where("status", 1)->where("user_id", $this->userID)->whereNotNull("offer_id")->get()->pluck("offer_id");
		$check = $this->course->offers()->whereIn("offer_id", $offersIDS)->where("course_id", $this->course->id)->get();
		if ($check->isNotEmpty()) {
			return 1;
		}
		return 0;
	}

	public function checkLectureSubscribed($lecture)
	{
		$subscription_duration = $this->course->subscription_duration;
		$statusOrder = Order::where("course_id", $this->course->id)->where("lecture_id", $lecture->id)->where("user_id", $this->userID)
			->when($subscription_duration, function ($query) use ($subscription_duration) {
				return $query->whereRaw("DATE_ADD(created_at, INTERVAL $subscription_duration MONTH) > ?", [now()]);
			})->first();
		return $statusOrder ? $statusOrder->status : 0;
	}

	public function isCurrentLecture($lecture, $chapter_id)
	{
		$CurrentLecture = false;
		$lectureSubscribed = $this->checkLectureSubscribed($lecture);
		// check is last lesson or next lesson
		if (($this->CheckQuestionsLectureUser && ($this->nextLectureID === $lecture->id)) || $lectureSubscribed) {
			$CurrentLecture = true;
		}
		$questions = Question::where("course_id", $this->course->id)->where("lecture_id", $lecture->id)->get();
		$lecture->assignments_count = (bool)$questions->where("related", "assignments")->count();
		$lecture->questions_count = (bool)$questions->where("related", "exams")->count();
		$lecture->attachments_count = (bool)$lecture->attachments->count();
		$this->course->count_attachments += $lecture->attachments->count();
		$checkLectureUser = (bool)LectureUser::where("course_id", $this->course->id)->where("user_id", $this->userID)->where("lecture_id", $lecture->id)->first();
		if ($lecture->questions_count && !$checkLectureUser && $this->CheckQuestionsLectureUser) {
			$this->CheckQuestionsLectureUser = false;
		}


		if ((!$lecture->questions_count || $checkLectureUser || !$this->course->subscribed) && $CurrentLecture) {
			$nextLectureID = Lecture::where("course_id", $this->course->id)->where("chapter_id", $chapter_id)->where("status", 1)->orderByRaw('CAST(`order` AS UNSIGNED) ASC')->where('order', '>', $lecture->order)->first();
			$this->nextLectureID = $nextLectureID ? $nextLectureID->id : $this->nextLectureID;
		}

		return $CurrentLecture;
	}

	public function checkOrder($lecture)
	{
		$statusOrder = $this->checkLectureSubscribed($lecture);
		return (($this->course->subscribed === 1 || $statusOrder === 1 || $lecture->type === 0) && $this->user != null);
	}

	public function lectures($lectures, $chapter_id)
	{
		foreach ($lectures as $lecture) {
			// check is current lesson
			$CurrentLecture = $this->isCurrentLecture($lecture, $chapter_id);
			// check user Subscribed lesson
			$check = $this->checkOrder($lecture);
			$lecture->subscribed = false;
			if ($check && $CurrentLecture) {
				$lecture->subscribed = true;
			}
		}
	}

	public function chapters()
	{
		foreach ($this->course->chapters as $chapter) {
			$lectureFirst = Lecture::where("course_id", $this->course->id)->where("chapter_id", $chapter->id)->where("status", 1)->orderByRaw('CAST(`order` AS UNSIGNED) ASC')->first();
			$this->nextLectureID = $lectureFirst ? $lectureFirst->id : $this->nextLectureID;
			$this->lectures($chapter->lectures, $chapter->id);
		}
	}


	public function course()
	{
		if (!$this->course) {
			return false;
		}
		$this->chapters();
		return $this->course;
	}
}
