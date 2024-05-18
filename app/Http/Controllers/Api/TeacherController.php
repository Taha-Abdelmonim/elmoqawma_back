<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Offer;
use App\Models\Review;
use App\Models\Section;
use App\Models\Subject;
use App\Models\SubjectSection;
use App\Models\Teacher;
use App\Models\TeacherSubject;
use App\Models\TeacherSectionSubject;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
	public $limit = 25;

	public function home()
	{
		global $limit;
		try {
			$teachers = Teacher::inRandomOrder()->limit($limit)->get();
			foreach ($teachers as $teacher) {
				$teacher->courses = Subject::whereIn("id", $teacher->subjects->pluck("subject_id"))->get();
			}
			$sections = Section::inRandomOrder()->limit($limit)->get();
			$subjects = Subject::inRandomOrder()->limit($limit)->get();
			$offers = Offer::with("currency")->inRandomOrder()->limit($limit)->get();
			$courses = Course::with(["section", "teacher", "subject", "currency", "comments"])->where("status", 1)->inRandomOrder()->limit($limit)->get();
			$reviews = Review::with(["user", "section"])->where('status', 1)->inRandomOrder()->limit($limit)->get();
			return returnData(
				[
					"teachers" => $teachers,
					"sections" => $sections,
					"subjects" => $subjects,
					"offers" => $offers,
					"courses" => $courses,
					"reviews" => $reviews,
				]);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function courses()
	{
		try {
			$courses = Course::with(["section", "teacher", "subject", "currency", "comments"])->where("status", 1)->orderBy("id", "ASC")->get();
			return returnData($courses);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function teachers()
	{
		try {
			$teachers = Teacher::orderBy("id", "ASC")->get();
			foreach ($teachers as $teacher) {
				$teacher->courses = Subject::whereIn("id", $teacher->subjects->pluck("subject_id"))->get();
			}
			return returnData($teachers);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function sections()
	{
		try {
			$sections = Section::orderBy("id", "ASC")->get();
			return returnData($sections);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function subjects(Request $request)
	{
		try {
			$subjects = Subject::orderBy("id", "ASC")->get();
			return returnData($subjects);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function teacher($id)
	{
		try {
			$teacher = Teacher::find($id);
			$sectionsIDS = $teacher->sections->pluck("section_id");
			$sections = Section::whereIn("id", $sectionsIDS)->get();
			foreach ($sections as $section) {
				$section->courses = Course::with(["section", "teacher", "subject", "currency", "comments"])->where("status", 1)->where("teacher_id", $id)->where("section_id", $section->id)->get();
			}
			$teacher = $teacher->toArray();
			$teacher["sections"] = $sections;
			return returnData($teacher);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function section($id)
	{
		try {
			$section = Section::find($id);
			$subjectIDS = $section->subjects->pluck("subject_id");
			$subjects = Subject::whereIn("id", $subjectIDS)->get();

			foreach ($subjects as $subject) {
				$teacherIDS = $subject->teachers->pluck("teacher_id");
				unset($subject['teachers']);
				$subject->teachers = Teacher::whereIn("id", $teacherIDS)->get();
				foreach ($subject->teachers as $teacher) {
					$teacher->courses = Course::with(["section", "teacher", "subject", "currency", "comments"])->where("status", 1)->where("section_id", $id)->where("teacher_id", $teacher->id)->where("subject_id", $subject->id)->get();
				}
			}
			//			return response()->json($subjects);
			$section = $section->toArray();
			$section["subjects"] = $subjects;
			return returnData($section);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}

	public function subject($id)
	{
		try {
			$subject = Subject::find($id);
			$teacherIDS = $subject->teachers->pluck("teacher_id");
			$teachers = Teacher::whereIn("id", $teacherIDS)->get();
			foreach ($teachers as $teacher) {
				$sectionIDS = SubjectSection::where("subject_id", $id)->orderBy("id", "ASC")->get()->pluck("section_id");
				$teacher->sections = Section::whereIn("id", $sectionIDS)->get();
				foreach ($teacher->sections as $section) {
					$section->courses = Course::with(["section", "teacher", "subject", "currency", "comments"])->where("status", 1)->where("teacher_id", $teacher->id)->where("section_id", $section->id)->where("subject_id", $id)->get();
				}
			}

			$subject = $subject->toArray();
			$subject["teachers"] = $teachers;
			return returnData($subject);
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return returnError('', trans("global.any_mistake"));
		}
	}
}
