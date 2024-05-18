<?php

namespace App\Services\LectureServices;

use App\Traits\GlobalTrait;
use MacsiDigital\Zoom\Facades\Zoom;


class DeleteAllLectureService
{
	use GlobalTrait;

	public $lectures;

	function __construct($lectures)
	{
		$this->lectures = $lectures;
	}

	protected function deletLectures($chapter)
	{
		foreach ($this->lectures as $lecture) {
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
			$description = " تم حزف محاضرات (";
			$description .= ", " . $lecture->name_ar . " ";
			$lecture->deleteLectureUser($lecture->id);
			$lecture->delete();
		}
		$description .= " ) " . " في قسم " . $chapter->name_ar . " في دورة " . $chapter->course->name_ar;
		return $description;
	}


	public function delete($chapter)
	{
		$description = $this->deletLectures($chapter);
		transaction("lectures", $description);
		session()->put('success', trans("global.success_delete_all"));
		return redirect()->route('lectures', $chapter->id)->with('success', trans("global.success_delete"));

	}
}
