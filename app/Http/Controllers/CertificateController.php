<?php

namespace App\Http\Controllers;

use App\Http\Requests\CertificateRequest;
use App\Models\Course;
use App\Models\Certificate;
use App\Models\User;
use App\Notifications\CreateCertificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Imagick;
use PDF;

class CertificateController extends Controller
{
	public function courses()
	{
		$this->authorize("show_certificates");
		$courses = Course::Courses()->get();
		foreach ($courses as $course) {
			$course->certificates_count = Certificate::where("course_id", $course->id)->get()->count();
		}
		return view("certificates.courses", compact("courses"));
	}


	public function index($courseID)
	{
		$this->authorize("show_certificates");
		$course = Course::find($courseID);
		$certificates = Certificate::with("course", "user")->where("course_id", $courseID)->orderBy("id", "ASC")->get();
		return view("certificates.index", compact("course", "certificates"));
	}

	public function create($courseID)
	{
		$this->authorize("create_certificates");
		$course = Course::find($courseID);
		$users = User::orderBy("id", "DESC")->get();
		return view("certificates.create", compact("course", "users"));
	}

	public function store($courseID, CertificateRequest $request)
	{
//		$certificate->course_name = $certificate->course->name;
		$this->authorize("create_certificates");
		$randomNameFile = "$courseID/" . Str::random(50);
		$pathFile = "images/certificates/$randomNameFile";
		$request->merge(["course_id" => $courseID]);
		$request->merge(["file" => $randomNameFile]);
		$certificate = Certificate::create($request->all());
		Storage::disk('certificates')->makeDirectory("$courseID");
		PDF::loadView("pdf.certificates", compact("certificate"))->save("$pathFile.pdf");
		$imagick = new Imagick();
		$imagick->readImage("$pathFile.pdf");
		$imagick->writeImages("$pathFile.png", false);
		$certificate->message = "Create Certificate In Course " . $certificate->course->name;
		Notification::send($certificate->user, new CreateCertificate($certificate));
		$description = " تم إنشاء شهادة لمستخدم " . $certificate->username;
		transaction("certificates", $description);
		return redirect()->route("certificates", $courseID)->with("success", trans("global.success_create"));
	}

	public function edit($courseID, $id)
	{
		$this->authorize("edit_certificates");
		$certificate = Certificate::findorFail($id);
		$course = Course::find($courseID);
		$users = User::orderBy("id", "DESC")->get();
		return view("certificates.edit", compact("certificate", "course", "users"));
	}

	public function update($courseID, $id, CertificateRequest $request)
	{
		$this->authorize("edit_certificates");
		$certificate = Certificate::findorFail($id);
		$randomNameFile = "$courseID/" . Str::random(50);
		$pathFile = "images/certificates/$randomNameFile";
		$request->merge(["course_id" => $courseID]);
		$request->merge(["file" => $randomNameFile]);
		Storage::disk('certificates')->delete($certificate->file . ".pdf");
		Storage::disk('certificates')->delete($certificate->file . ".png");
		$certificate->update($request->all());
		PDF::loadView("pdf.certificates", compact("certificate"))->save("$pathFile.pdf");
		$imagick = new Imagick();
		$imagick->readImage("$pathFile.pdf");
		$imagick->writeImages("$pathFile.png", false);
		$description = " تم تعديل شهادة لمستخدم " . $certificate->username;
		transaction("certificates", $description);
		return redirect()->route("certificates", $courseID)->with("success", trans("global.success_update"));
	}

	public function destroy($courseID, $id)
	{
		$this->authorize("remove_certificates");
		$certificate = Certificate::findorFail($id);
		Storage::disk('certificates')->delete($certificate->file . ".pdf");
		Storage::disk('certificates')->delete($certificate->file . ".png");
		$description = " تم حزف شهادة لمستخدم" . $certificate->username;
		transaction("certificates", $description);
		$certificate->delete();
		return redirect()->route("certificates", $courseID)->with("success", trans("global.success_delete"));
	}

	public function destroyAll($courseID, Request $request)
	{
		$this->authorize("remove_certificates");
		$certificates = Certificate::whereIn("id", json_decode($request->ids))->get();
		$description = " تم حزف شهادات (";
		foreach ($certificates as $certificate) {
			Storage::disk('certificates')->delete($certificate->file . ".pdf");
			Storage::disk('certificates')->delete($certificate->file . ".png");
			$description .= ", " . $certificate->username . " ";
			$certificate->delete();
		}
		$description .= " )";
		transaction("certificates", $description);
		return redirect()->route("certificates", $courseID)->with("success", trans("global.success_delete_all"));
	}

	public function toggleStatus($id, Request $request)
	{

		$this->authorize('certificate_toggle_status');
		if ($request->ajax() and isset($request->status)) {
			$msg = $request->status == 1 ? trans("global.success_enabled") : trans("global.success_not_enabled");
			$certificate = Certificate::findorFail($id);
			$certificate->status = $request->status;
			$certificate->save();
			$user = $certificate->user;
			if ($request->status == 1) {
				$certificate->message = $msg . " الشهادة في دورة " . $certificate->course->name;
				Notification::send($user, new CreateCertificate($certificate));
			}
			$description = $msg . " الشهادة " . " لمستخدم " . $user->name() . " رقم الشهادة #" . $certificate->id;
			transaction("orders", $description);
			return returnData($certificate, $msg);
		}
		abort('301', 'unAuthenticated');
	}
}