<?php

namespace App\Services\CourseServices;

use App\Models\User;
use App\Models\Files;
use App\Models\Course;
use App\Models\Chapter;
use App\Models\Lecture;
use App\Traits\GlobalTrait;
use App\Notifications\CreateCourse;
use Illuminate\Support\Facades\Storage;
use MacsiDigital\Zoom\Facades\Zoom;
use Illuminate\Support\Facades\Notification;


class DeleteCourseService
{
	use GlobalTrait;

	public $course;

	function __construct($course)
	{
		$this->course = $course;
	}

	protected function files()
	{
		$oldPath = public_path("/images/{$this->course->photo}");
		if (is_file($oldPath)) {
			unlink($oldPath);
		}
		$oldPathDescription = public_path("/images/{$this->course->description_photo}");
		if (is_file($oldPathDescription)) {
			unlink($oldPathDescription);
		}
	}

	protected function deleteFiles()
	{
		$attachments = $this->course->attachments()->get();
		foreach ($attachments as $attachment) {
			Storage::disk('public')->delete($attachment->file);
			$attachment->delete();
		}
	}

	protected function deleteChapters()
	{
		$chapters = Chapter::where("course_id", $this->course->id)->get();
		foreach ($chapters as $chapter) {
			$lectures = Lecture::where("chapter_id", $chapter->id)->get();
			foreach ($lectures as $lecture) {
				if ($lecture->type_video === "server") {
					$streamApi = $this->connectBunny();
					try {
						$streamApi->deleteVideo(libraryId: env("BUNNY_LIBRARY_ID"), videoId: $lecture->videoID);
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
			$chapter->delete();
		}
	}

	protected function deleteCourse()
	{
		$this->course->delete();
	}

	public function delete()
	{
		$this->files();
		$this->deleteFiles();
		$this->deleteChapters();
		$this->deleteCourse();
		$description = " تم حذف` دورة ";
		transaction("courses", $description);
		session()->put("success", trans("global.success_delete"));
		return redirect()->route("courses");
	}
}
