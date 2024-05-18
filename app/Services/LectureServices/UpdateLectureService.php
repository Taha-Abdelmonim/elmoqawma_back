<?php

namespace App\Services\LectureServices;

use App\Models\Lecture;
use App\Traits\GlobalTrait;
use MacsiDigital\Zoom\Facades\Zoom;


class UpdateLectureService
{
	use GlobalTrait;

	public $lecture;

	function __construct($lecture)
	{
		$this->lecture = $lecture;
	}

	public function zoom($data)
	{
		Zoom::meeting()->find($this->lecture->videoID)->update(
			[
				"topic" => $data["title"],
				"duration" => $data["duration"],
				"start_time" => $data["start_time"],
			]
		);
		return true;
	}

	public function server($data)
	{
		$streamApi = $this->connectBunny();
		try {
			$streamApi->updateVideo(
				libraryId: env("BUNNY_LIBRARY_ID"),
				videoId: $this->lecture->videoID,
				body: [
					'title' => request()->title,
				],
			);
		} catch (\Exception $ex) {
			returnError($ex->getCode(), $ex->getMessage());
		}
		return true;
	}

	protected function setData($data)
	{
		$method = request()->type_video;
		if ($method === "server" || $method === "zoom") {
			$data = $this->$method($data);
		}
		return $data;
	}


	protected function updateLecture($data)
	{
		$lecture = $this->lecture->update($data);
		return $lecture;
	}

	public function update($request)
	{
		$data = $request->all();
		$data = $this->setData($data);
		$lecture = $this->updateLecture($data);
		$chapter = $this->lecture->chapter;
		$description = " تم تعديل محاضرة " . $request->name_ar . " في قسم " . $chapter->name_ar . " في دورة " . $chapter->course->name_ar;
		transaction("lectures", $description);
		return redirect()->route('lectures', $chapter->id)->with('success', trans("global.success_update"));

	}
}
