<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CoursesRequest;
use App\Models\{Course, Chapter, Lecture, Section, Subject, Teacher};
use App\Services\CourseServices\{StoreCourseService, DeleteCourseService, UpdateCourseService, UploadVideoService};

class CourseController extends Controller
{
	public function getCourses(Request $request)
	{
		$courses = Course::Courses()
			->with("section", "teacher", "subject")
			->get();
		return response(["data" => $courses]);
	}

	public function index()
	{
		$this->authorize("show_courses");
		return view("courses.courses");
	}

	public function create()
	{
		$this->authorize("create_courses");
		$teachers = Teacher::orderBy("id", "ASC")->get();
		$sections = Section::orderBy("id", "ASC")->get();
		$subjects = Subject::orderBy("id", "ASC")->get();
		if (auth()->user()->teacher_id !== null) {
			$teacher = Teacher::find(auth()->user()->teacher_id);
			$sectionsIDS = $teacher->sections->pluck("section_id");
			$sections = Section::whereIn("id", $sectionsIDS)->get();
			$subjectsIDS = $teacher->subjects->pluck("subject_id");
			$subjects = Subject::whereIn("id", $subjectsIDS)->get();
		}
		//		$currencies = Currency::orderBy('id', 'ASC')->get();
		return view("courses.create", compact("sections", "teachers", "subjects"));
	}

	public function store(CoursesRequest $request)
	{
		$this->authorize("create_courses");
		$this->validate(
			$request,
			['photo' => 'mimes:jpeg,jpg,png,gif,webp,svg|required'],
			['photo.required' => trans("validation.photo_required"), 'photo.mimes' => trans("validation.photo_mimes")]
		);
		return (new StoreCourseService())->store($request);
	}

	public function show($id, Request $request)
	{
		$this->authorize("show_courses");
		$course = Course::with("teacher", "subject", "section")->findorFail($id);
		$attachments = $course->attachments;
		$chapters = Chapter::where("course_id", $id)->get();
		$lectures = Lecture::whereIn("chapter_id", $chapters->pluck("id"))->get()->groupBy("chapter_id");
		$lecturesFlat = Lecture::whereIn("chapter_id", $chapters->pluck("id"))->get()->pluck("chapter_id")->unique();
		$chaptersBlanks = Chapter::where("course_id", $id)->whereIn("id", array_diff($chapters->pluck("id")->toArray(), $lecturesFlat->toArray()))->get();
		return view("courses.show", compact("course", "chapters", "lectures", "chaptersBlanks", "attachments"));
	}

	public function edit($id)
	{
		$this->authorize("edit_courses");
		$course = Course::findorFail($id);
		$course->subject;
		$selectedSection = Section::where("id", $course->section_id)->first();
		$teachers = Teacher::orderBy("id", "ASC")->get();
		$sections = Section::orderBy("id", "DESC")->get();
		$subjects = Subject::orderBy("id", "ASC")->get();
		if (auth()->user()->teacher_id !== null) {
			$teacher = Teacher::find(auth()->user()->teacher_id);
			$sectionsIDS = $teacher->sections->pluck("section_id");
			$sections = Section::whereIn("id", $sectionsIDS)->get();
			$subjectsIDS = $teacher->subjects->pluck("subject_id");
			$subjects = Subject::whereIn("id", $subjectsIDS)->get();
		}
		//		$currencies = Currency::orderBy('id', 'ASC')->get();
		return view("courses.edit", compact("course", "sections", "selectedSection", "teachers", "subjects"));
	}

	public function update($id, CoursesRequest $request)
	{
		$this->authorize("edit_courses");
		$course = Course::findorFail($id);
		return (new UpdateCourseService($course))->update($request);
	}

	public function destroy($id)
	{
		$this->authorize("remove_courses");
		$course = Course::findorFail($id);
		return (new DeleteCourseService($course))->delete();
	}


	public function uploadVideo($id, Request $request)
	{
		return (new UploadVideoService())->upload($request, $id);
	}

	public function getCourseLectures($id)
	{
		$course = Course::findorFail($id);
		return view("coupons.course_lectures", ["lectures" => $course->lectures]);
	}
}
