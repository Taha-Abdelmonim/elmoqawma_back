<?php

namespace App\Services\Api;

use App\Models\Course;
use App\Models\DetailExam;
use App\Models\Certificate;
use App\Models\Lecture;
use App\Models\LectureUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;
use PDF;

class ExamRegisterService
{
	public $user, $course, $data, $pathFile;

	public function __construct($request)
	{
		$this->data = $request->all();
		$this->user = auth()
			->guard("api")
			->user();
		$this->data["user_id"] = $this->user->id;
		$this->course = Course::find(request()->course_id);
	}

	public function examDetails()
	{
		$lecture = Lecture::find(request()->lecture_id);
		$detailExams = DetailExam::create($this->data);
		$detailExams->available_re_exam =
			DetailExam::whereDate("created_at", Carbon::today())
				->where("course_id", request()->course_id)
				->where("lecture_id", request()->lecture_id)
				->where("user_id", $this->user->id)
				->where("score", "<=", (int)env("SCORE"))
				->count() >= $lecture->re_exam_count;
		return $detailExams;
	}

	public function userAnswers($detailExams)
	{
		$answers = $detailExams->userAnswers()->createMany(request()->answers);
		return $answers;
	}

	public function lecture()
	{
		$lectureUser = LectureUser::where("course_id", $this->course->id)
			->where("lecture_id", request()->lecture_id)
			->where("user_id", $this->user->id)
			->where("status", 1)
			->first();
		if (empty($lectureUser)) {
			LectureUser::create($this->data);
		}
	}

	public function setupCertificate()
	{
		if (request()->latest) {
			$certificate = Certificate::where("user_id", $this->user->id)
				->where("course_id", $this->course->id)
				->first();
			if ($certificate === null) {
				Storage::disk("certificates")->makeDirectory("{$this->course->id}");
			} else {
				Storage::disk("certificates")->delete($certificate->file . ".pdf");
				Storage::disk("certificates")->delete($certificate->file . ".png");
			}
			$randomNameFile = "{$this->course->id}/" . Str::random(50);
			$pathFile = "images/certificates/$randomNameFile";
			if ($certificate === null) {
				$this->data["username"] = $this->user->name_ar;
				$this->data["file"] = $randomNameFile;
				$certificate = Certificate::create($this->data);
			} else {
				$certificate->update(["score" => request()->score, "file" => $randomNameFile]);
			}

			PDF::loadView("pdf.certificates", compact("certificate"))->save("$pathFile.pdf");
			$imagick = new Imagick();
			$imagick->readImage("$pathFile.pdf");
			$imagick->writeImages("$pathFile.png", false);
			$this->pathFile = $pathFile;
		}
	}

	public function register()
	{
		$detailExams = $this->examDetails();
		$this->userAnswers($detailExams);
		if ((int)request()->score >= env("SCORE")) {
			$this->lecture();
			$this->setupCertificate();
		}
		$detailExams->file = $this->pathFile;
		return returnData($detailExams);
	}
}
