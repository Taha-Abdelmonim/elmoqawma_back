<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;

class SectionController extends Controller
{
	public function getSections(Request $request)
	{
		date_default_timezone_set("Africa/Cairo");
		$sections = Section::orderBy('id', 'DESC')->get();
		if ($request->has("section_id")) {
			$section = Section::find($request->section_id);
			$sections = Section::where("section_id", $request->section_id)->orderBy('id', 'DESC')->get();
			$sections->push($section);
		}
		foreach ($sections as $section) {
			$section->section = $section->section($section->section_id);
		}
		return response(['data' => $sections]);
	}

	public function getChildSection(Request $request)
	{
		$sections = Section::where("section_id", $request->id)->orderBy('id', 'DESC')->get();
		if ($sections->isNotEmpty()) {
			return returnData("" . view("sections.child_sections", compact("sections")));
		}
		return "";
	}

	public function index()
	{
		$this->authorize('show_sections');
//    $sections = Section::orderBy('id', 'DESC')->get();
		return view('sections.sections');

	}

	public function create()
	{
		//    whereNull("section_id")->
		$this->authorize('create_sections');
		return view('sections.create');
	}

	public function store(SectionRequest $request)
	{
		$this->authorize('create_sections');
		$data = $request->all();
		if ($request->file('photo')) {
			$data["photo"] = $request->file('photo')->store("sections", 'public_image');
		}
		Section::create($data);
		$description = " تم إنشاء قسم " . $request->name_ar;
		transaction("sections", $description);
		session()->put('success', trans("global.success_create"));
		return redirect()->route('sections');
	}

	public function show(Section $section)
	{
		$this->authorize('show_sections');
	}

	public function edit($id)
	{
		$this->authorize('edit_sections');
		$editSection = Section::findorFail($id);
		$selectedSection = Section::where("id", $editSection->section_id)->first();
		return view('sections.edit', compact('editSection', "selectedSection"));
	}

	public function update($id, SectionRequest $request)
	{
		$this->authorize('edit_sections');
		$data = $request->all();
		$section = Section::findorFail($id);
		if ($request->file('photo')) {
			$oldPath = public_path("/images/{$section->photo}");
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$data["photo"] = $request->file('photo')->store("sections", 'public_image');
		}
		$section->update($data);
		$description = " تم تعديل قسم " . $section->name_ar;
		transaction("sections", $description);
		session()->put('success', trans("global.success_update"));
		return redirect()->route('sections');
	}

	public function destroy($id)
	{
		$this->authorize('remove_sections');
		$section = Section::findorFail($id);
		$oldPath = public_path("/images/$section->photo");
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		$description = " تم حزف قسم " . $section->name_ar;
		$section->delete();
		transaction("sections", $description);
		session()->put('success', trans("global.success_delete"));
		return redirect()->route('sections');
	}

	public function destroyAll(Request $request)
	{
		$this->authorize('remove_sections');
		$sections = Section::whereIn('id', json_decode($request->ids))->get();
		$description = " تم حذف اقسام (";
		foreach ($sections as $section) {
			$description .= ", " . $section->name_ar . " ";
			$oldPath = public_path("/images/$section->photo");
			if (is_file($oldPath)) {
				unlink($oldPath);
			}
			$section->delete();
		}
		$description .= " )";
		transaction("sections", $description);
		session()->put('success', trans("global.success_delete_all"));
		return redirect()->route("sections");
	}
}
