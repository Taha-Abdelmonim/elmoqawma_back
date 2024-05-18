<?php

namespace App\Services\LectureServices;

use App\Traits\GlobalTrait;
use MacsiDigital\Zoom\Facades\Zoom;


class DeleteLectureService
{
	use GlobalTrait;

	public $lecture, $chapter;

	function __construct($lecture)
	{
		$this->lecture = $lecture;
		$this->chapter = $this->lecture->chapter;
	}

	protected function deletLectureServers()
	{
		if ($this->lecture->type_video === "zoom") {
			$zoom = Zoom::meeting()->find($this->lecture->videoID);
			if ($zoom != null) {
				$zoom->delete();
			}
		} else if ($this->lecture->type_video === "server") {
			$streamApi = $this->connectBunny();
			try {
				$streamApi->deleteVideo(
					libraryId: env("BUNNY_LIBRARY_ID"),
					videoId: $this->lecture->videoID
				);
			} catch (\Exception $ex) {
				returnError($ex->getCode(), $ex->getMessage());
			}
		}
		$chapter = $this->chapter;
		$description = " تم حزف محاضرة " . $this->lecture->name_ar . " في قسم " . $chapter->name_ar . " في دورة " . $chapter->course->name_ar;
		return $description;
	}

	public function deleteLectures()
	{
		$this->lecture->deleteLectureUser($this->lecture->id);
		$this->lecture->delete();
	}

	public function delete()
	{
		$description = $this->deletLectureServers();
		$this->deleteLectures();
		transaction("lectures", $description);

	}
}
