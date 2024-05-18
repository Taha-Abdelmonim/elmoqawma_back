<?php

namespace App\Http\Controllers;

use App\Models\AmountSubject;
use App\Models\Section;
use App\Models\Subject;
use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Models\Currency;
use App\Models\SubjectSection;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
	public function getSubjects(Request $request)
	{
		// Expenses
		$subjects = Subject::orderBy("id", "ASC")->get();
		return response(["data" => $subjects]);
	}

	public function index()
	{
		return view("subjects.index");
	}

	public function create()
	{
		// $this->authorize("create_subjects");
		$sections = Section::orderBy("id", "ASC")->get();
		return view("subjects.create", compact("sections"));
	}

	public function store(SubjectRequest $request)
	{
		// $this->authorize("create_subjects");
		$this->validate(
			$request,
			['photo' => 'mimes:jpeg,jpg,png,gif,webp,svg|required'],
			['photo.required' => trans("validation.photo_required"), 'photo.mimes' => trans("validation.photo_mimes")]
		);
		$data = $request->all();
		if ($request->file('photo')) {
			$photo = $request->file('photo')->store("subjects", 'public_image');
		}
		$data["photo"] = $photo;
		$subject = Subject::create($data);
		if ($request->has('sections')) {
			foreach ($request->sections as $section) {
				SubjectSection::create([
					"section_id" => $section["section_id"],
					"subject_id" => $subject->id,
				]);
			}
		}

		$description = " تم إنشاء مادة " . $subject->name_ar;
		transaction("subjects", $description);
		$request->session()->put("success", trans("global.success_create"));
		return redirect()->route("subjects");
	}

	public function show(Subject $subject)
	{
		//
	}

	public function edit($id)
	{
		// $this->authorize("edit_subjects");
		$subject = Subject::findorFail($id);
		$sections = Section::orderBy("id", "ASC")->get();
		$subjectSections = Section::whereIn("id", $subject->editSections->pluck("section_id"))->get();
		return view("subjects.edit", compact("subject", "sections", "subjectSections"));
	}

	public function update($id, SubjectRequest $request)
	{
		// $this->authorize("edit_subjects");

		$subject = Subject::findorFail($id);
		$photo = $subject->photo;
		if ($request->file('photo')) {
			$oldPath = public_path("/images/$photo");
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$photo = $request->file('photo')->store("subjects", 'public_image');
		}
		$data = $request->all();
		$data["photo"] = $photo;
		$subject->update($data);

		if ($request->delete_items != "[]") {
			SubjectSection::where("subject_id", $id)->whereIn("section_id", json_decode($request->delete_items))->delete();
		}
		if ($request->has('sections')) {
			foreach ($request->sections as $section) {
				SubjectSection::create([
					"section_id" => $section["section_id"],
					"subject_id" => $subject->id,
				]);
			}
		}
		$description = " تم تعديل مادة " . $subject->name_ar;
		transaction("subjects", $description);
		$request->session()->put("success", trans("global.success_update"));
		return redirect()->route("subjects");
	}

	public function destroy($id)
	{
		// $this->authorize("remove_subjects");
		$subject = Subject::findorFail($id);
		$oldPath = public_path("/images/$subject->photo");
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		SubjectSection::where("subject_id", $id)->orderBy("id", "ASC")->delete();
		$description = " تم حزف مادة " . $subject->name_ar;
		transaction("subjects", $description);
		$subject->delete();
		session()->put("success", trans("global.success_delete"));
		return redirect()->route("subjects");
	}

	public function destroyAll(Request $request)
	{
		// $this->authorize("remove_subjects");

		$subjects = Subject::whereIn("id", json_decode($request->ids))->get();
		$description = " تم حزف مواد (";
		foreach ($subjects as $subject) {
			$oldPath = public_path("/images/$subject->photo");
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			SubjectSection::where("subject_id", $subject->id)->orderBy("id", "ASC")->delete();
			$description .= ", " . $subject->name_ar . " ";
			$subject->delete();
		}
		$description .= " )";
		transaction("subjects", $description);
		session()->put("success", trans("global.success_delete_all"));
		return redirect()->route("subjects");
	}
}
