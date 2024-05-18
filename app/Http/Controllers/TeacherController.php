<?php

namespace App\Http\Controllers;

use App\Models\AmountTeacher;
use App\Models\Course;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\Currency;
use App\Models\TeacherSubject;
use App\Models\TeacherSectionSubject;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
	public function getTeachers()
	{
		// Expenses
		$teachers = Teacher::orderBy("id", "ASC")->get();

		return response(["data" => $teachers]);
	}

	public function index()
	{
		$this->authorize("show_teachers");


		return view("teachers.index");
	}

	public function create()
	{
		$this->authorize("create_teachers");
		$sections = Section::orderBy("id", "ASC")->get();
		$subjects = Subject::orderBy("id", "DESC")->get();

		return view("teachers.create", compact("sections", "subjects"));
	}

	public function store(TeacherRequest $request)
	{
//		dd($request);
		$this->authorize("create_teachers");
		$this->validate(
			$request,
			['photo' => 'mimes:jpeg,jpg,png,gif,webp,svg|required'],
			['photo.required' => trans("validation.photo_required"), 'photo.mimes' => trans("validation.photo_mimes")]
		);
		if ($request->file('photo')) {
			$photo = $request->file('photo')->store("teachers", 'public_image');
		}
		$data = $request->all();
		$data["photo"] = $photo;
		$teacher = Teacher::create($data);

		if ($request->has('sections')) {
			foreach ($request->sections as $section) {
				TeacherSectionSubject::create([
					"section_id" => $section["section_id"],
					"teacher_id" => $teacher->id,
				]);
			}
		}
		if ($request->has('subjects')) {
			foreach ($request->subjects as $subject) {
				TeacherSectionSubject::create([
					"subject_id" => $subject["subject_id"],
					"teacher_id" => $teacher->id,
				]);
			}
		}
		$description = " تم إنشاء مدرب " . $teacher->name_ar;
		transaction("teachers", $description);
		$request->session()->put("success", trans("global.success_create"));
		return redirect()->route("teachers");
	}

	public function getSectionsCourses($id)
	{
		//
	}

	public function edit($id)
	{
		$this->authorize("edit_teachers");
		$teacher = Teacher::findorFail($id);
		$sections = Section::orderBy("id", "ASC")->get();
		$subjects = Subject::orderBy("id", "DESC")->get();
		$teacherSectionsSubjects = TeacherSectionSubject::where("teacher_id", $id)->orderBy("id", "ASC")->get();
		$teacherSections = Section::whereIn("id", $teacherSectionsSubjects->whereNotNull("section_id")->pluck("section_id"))->get();
		$teacherSubjects = Subject::whereIn("id", $teacherSectionsSubjects->whereNotNull("subject_id")->pluck("subject_id"))->get();


		return view("teachers.edit", compact("teacher", "sections", "subjects", "teacherSections", "teacherSubjects"));
	}

	public function update($id, TeacherRequest $request)
	{
		$this->authorize("edit_teachers");
		$teacher = Teacher::findorFail($id);
		$photo = $teacher->photo;
		if ($request->file('photo')) {
			$oldPath = public_path("/images/$photo");
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$photo = $request->file('photo')->store("teachers", 'public_image');
		}
		$data = $request->all();
		$data["photo"] = $photo;
		$teacher->update($data);
		if ($request->delete_items_sections != "[]") {
			TeacherSectionSubject::where("teacher_id", $id)->whereIn("section_id", json_decode($request->delete_items_sections))->delete();
		}
		if ($request->delete_items_subjects != "[]") {
			TeacherSectionSubject::where("teacher_id", $id)->whereIn("subject_id", json_decode($request->delete_items_subjects))->delete();
		}

		if ($request->has('sections')) {
			foreach ($request->sections as $section) {
				TeacherSectionSubject::create([
					"section_id" => $section["section_id"],
					"teacher_id" => $teacher->id,
				]);
			}
		}
		if ($request->has('subjects')) {
			foreach ($request->subjects as $subject) {
				TeacherSectionSubject::create([
					"subject_id" => $subject["subject_id"],
					"teacher_id" => $teacher->id,
				]);
			}
		}
		$description = " تم تعديل المدرس " . $teacher->name_ar;
		transaction("teachers", $description);
		$request->session()->put("success", trans("global.success_update"));
		return redirect()->route("teachers");
	}

	public function destroy($id)
	{
		$this->authorize("remove_teachers");
		$teacher = Teacher::findorFail($id);
		$oldPath = public_path("/images/$teacher->photo");
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		TeacherSectionSubject::where("teacher_id", $id)->orderBy("id", "ASC")->delete();
		$description = " تم حزف المدرس " . $teacher->name_ar;
		transaction("teachers", $description);
		$teacher->delete();
		session()->put("success", trans("global.success_delete"));
		return redirect()->route("teachers");
	}
}
