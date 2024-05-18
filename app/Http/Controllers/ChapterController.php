<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use MacsiDigital\Zoom\Facades\Zoom;

class ChapterController extends Controller
{

	public function index($course_id)
	{
		$course = Course::find($course_id);
		$chapters = Chapter::where("course_id", $course_id)->orderBy('id', 'ASC')->get();
		return view("chapters.index", compact("course", "chapters"));
	}


	public function create($course_id)
	{
		$course = Course::find($course_id);
		return view("chapters.create", compact("course"));
	}


	public function store($course_id, ChapterRequest $request)
	{
		$this->authorize('create_chapters');
		$data = $request->all();
		$data["course_id"] = $course_id;
		$chapter = Chapter::create($data);
		$course = $chapter->course->subject->name_ar;
		$description = " تم إضافة قسم " . $request->name_ar . " الي دورة " . $course;
		transaction("chapters", $description);
		return redirect()->route('chapters', $course_id)->with('success', trans("global.success_create"));
	}


	public function show(Request $request)
	{
//		return returnData(Chapter::findorFail($request->id));
	}


	public function edit($course_id, $id)
	{
		$this->authorize('edit_chapters');
		$chapter = Chapter::find($id);
		$course = $chapter->course;
		return view('chapters.edit', compact('course', "chapter"));
	}


	public function update($course_id, $id, ChapterRequest $request)
	{
		$this->authorize('edit_chapters');
		$chapter = Chapter::find($id);
		$chapter->update($request->all());
		$course = $chapter->course->subject->name_ar;
		$description = " تم تعديل قسم  " . $request->name_ar . " في دورة " . $course;

		transaction("chapters", $description);
		return redirect()->route('chapters', $course_id)->with('success', trans("global.success_update"));
	}


	public function destroy($course_id, $id)
	{
		$this->authorize('remove_chapters');
		$chapter = Chapter::findorFail($id);
		$lectures = Lecture::where("chapter_id", $chapter->id)->get();
		foreach ($lectures as $lecture) {
			if ($lecture->type_video === "server") {
				$streamApi = $this->connectBunny();
				try {
					$streamApi->deleteVideo(
						libraryId: env("BUNNY_LIBRARY_ID"),
						videoId: $lecture->videoID);
				} catch (\Exception $ex) {
					returnError($ex->getCode(), $ex->getMessage());
				}
			} elseif ($lecture->type_video === "zoom") {
				$zoom = Zoom::meeting()->find($lecture->videoID);
				if ($zoom != null) {
					$zoom->delete();
				}
			}
			$lecture->deleteLectureUser($lecture->id);
			$lecture->delete();
		}
		$course = $chapter->course->subject->name_ar;
		$description = " تم حزف قسم دورة " . $chapter->name_ar . " من دورة " . $course;
		$chapter->delete();
		transaction("chapters", $description);
		return redirect()->route('chapters', $course_id)->with('success', trans("global.success_update"));
	}

	public function destroyAll($course_id, Request $request)
	{
		$this->authorize('remove_chapters');
		$chapters = Chapter::whereIn('id', json_decode($request->ids))->get();
		$description = " تم حزف فصول (";
		foreach ($chapters as $chapter) {
			$lectures = Lecture::where("chapter_id", $chapter->id)->get();
			foreach ($lectures as $lecture) {
				if ($lecture->type_video === "server") {
					$streamApi = $this->connectBunny();
					try {
						$streamApi->deleteVideo(
							libraryId: env("BUNNY_LIBRARY_ID"),
							videoId: $lecture->videoID);
					} catch (\Exception $ex) {
						returnError($ex->getCode(), $ex->getMessage());
					}
				} elseif ($lecture->type_video === "zoom") {
					$zoom = Zoom::meeting()->find($lecture->videoID);
					if ($zoom != null) {
						$zoom->delete();
					}
				}
				$lecture->deleteLectureUser($lecture->id);
				$lecture->delete();
			}
			$description .= ", " . $chapter->name_ar . " ";
			$chapter->delete();
		}
		$course = Course::find($course_id);
		$description .= " ) في دورة " . $course->name;
		transaction("chapters", $description);
		session()->put('success', trans("global.success_delete_all"));
		return redirect()->route('chapters', $course_id)->with('success', trans("global.success_update"));
	}
}
