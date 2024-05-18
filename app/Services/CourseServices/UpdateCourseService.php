<?php

namespace App\Services\CourseServices;

use App\Models\User;
use App\Models\Course;
use App\Traits\GlobalTrait;
use App\Notifications\CreateCourse;
use Illuminate\Support\Facades\Notification;


class UpdateCourseService
{
    use GlobalTrait;
    public $course;
    function __construct($course)
    {
        $this->course = $course;
    }

    protected function setData($data)
    {
        if (request()->file("photo")) {
            $oldPath = public_path("/images/{$this->course->photo}");
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
            $data["photo"] = request()->file("photo")->store("courses", "public_image");
        }
        if (request()->file("description_photo")) {
            $oldPathDescription = public_path("/images/{$this->course->description_photo}");
            if (is_file($oldPathDescription)) {
                unlink($oldPathDescription);
            }
            $data["description_photo"] = request()->file("description_photo")->store("courses", "public_image");
        }
        return $data;
    }


    protected function updateCourse($data)
    {
        $course = $this->course->update($data);
        return $course;
    }

    public function update($request)
    {
        $data = $request->all();
        $data = $this->setData($data);
        $this->updateCourse($data);
        $description = " تم تعديل دورة " . $this->course->name_ar;
        transaction("courses", $description);
        session()->put("success", trans("global.success_update"));
        return redirect()->route("courses");
    }
}
