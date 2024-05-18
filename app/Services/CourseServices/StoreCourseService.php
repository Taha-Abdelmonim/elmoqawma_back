<?php

namespace App\Services\CourseServices;

use App\Models\User;
use App\Models\Course;
use App\Traits\GlobalTrait;
use App\Notifications\CreateCourse;
use Illuminate\Support\Facades\Notification;


class StoreCourseService
{
    use GlobalTrait;

    protected function setData($data)
    {
        if (auth()->user()->teacher_id !== null) {
            $data["teacher_id"] = auth()->user()->teacher_id;
        }
        if (request()->file("photo")) {
            $data["photo"] = request()->file("photo")->store("courses", "public_image");
        }
        if (request()->file("description_photo")) {
            $data["description_photo"] = request()->file("description_photo")->store("courses", "public_image");
        }
        $data["collectionID"] = "";
        return $data;
    }


    protected function createCourse($data)
    {
        $course = Course::create($data);
        return $course;
    }

    protected function uploadCourse($course)
    {
        $streamApi = $this->connectBunny();
        $collection = $streamApi
            ->createCollection(
                libraryId: env("BUNNY_LIBRARY_ID"),
                body: [
                    "name" => $course->name . " " . $course->teacher->name_ar,
                ]
            )
            ->getContents();
        $course->update(["collectionID" => $collection["guid"]]);
        return $course;
    }

    protected function sendNotification($course)
    {
        $users = User::where("id", "!=", auth()->user()->id)->get();
        Notification::send($users, new CreateCourse($course));
    }

    public function store($request)
    {
        $data = $request->all();
        if (auth()->user()->teacher_id === null && !$request->has("teacher_id")) {
            return redirect()->back()->with("error", "حقل المدرس مطلوب");
        }
        $data = $this->setData($data);
        $course = $this->createCourse($data);
        $uploadBunny = $this->uploadCourse($course);
        $sendNotification = $this->uploadCourse($uploadBunny);
        $description = " تم إنشاء دورة " . $request->name_ar;
        transaction("courses", $description);
        session()->put("success", trans("global.success_create"));
        return redirect()->route("courses");
    }
}
