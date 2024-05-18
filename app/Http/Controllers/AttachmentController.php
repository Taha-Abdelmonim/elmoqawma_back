<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{

	public function create($modal, $modal_id)
	{
		return view('attachments.create', compact('modal', 'modal_id'));
	}

	public function store($modal, $modal_id, Request $request)
	{
		$attachment = new Attachment(['name' => $request->name, 'file' => $request->file->store('attachments', 'public_image')]);
		if ($modal === 'lecture') {
			$model = Lecture::find($modal_id);
		} elseif ($modal === 'course') {
			$model = Course::find($modal_id);
		}
		$model->attachments()->save($attachment);
		return redirect()->back()->with('success', __('global.success_create'));
	}

	public function destroy($id)
	{
		$attachment = Attachment::find($id);
		Storage::disk('public_image')->delete($attachment->file);
		$attachment->delete();
		return redirect()->back()->with('success', __('global.success_delete'));
	}
}
