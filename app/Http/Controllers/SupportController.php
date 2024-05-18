<?php

namespace App\Http\Controllers;

use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
	public function index()
	{
		$this->authorize('show_supports');
		$supports = Support::all()->sortDesc();
		return view('supports.support', compact('supports'));
	}

	public function create()
	{
		$this->authorize('create_support');
	}

	public function store(Request $request)
	{
		$this->authorize('create_support');
	}

	public function getChat($id)
	{
		$this->authorize('show_supports');
		$chat = Support::findorFail($id);
		$chat->update([
			'done_read' => 1,
		]);
		return view('supports.content', compact('chat'));
	}

	public function doneContact($id)
	{
		$this->authorize('edit_supports');
		$chat = Support::findorFail($id);
		$chat->update([
			'done_contact' => 1,
		]);
		$user = $chat->user->name();
		$description = " تم التواصل مع المستخدم " . $user;
		transaction("supports", $description);
		return returnData('', trans("global.support_done_contact"));
	}

	public function doneProblem($id)
	{
		$this->authorize('edit_supports');
		$chat = Support::findorFail($id);
		$chat->update([
			'done_problem' => 1,
		]);
		$user = $chat->user->name();
		$description = " تم حل المشكلة لمستخدم " . $user;
		transaction("supports", $description);
		return returnData('', trans("global.support_done_problem"));
	}

	public function chatDelete($id)
	{
		$this->authorize('remove_supports');
		$chat = Support::findorFail($id);
		$user = $chat->user->name();
		$description = " تم حزف الشات لمستخدم " . $user;
		transaction("supports", $description);
		$chat->destroy($id);
		return returnData('', trans("global.success_deleted_chat"));
	}

	public function show(Support $support)
	{
		$this->authorize('show_supports');
	}

	public function edit(Support $support)
	{
		$this->authorize('edit_supports');
	}

	public function update(Request $request, Support $support)
	{
		$this->authorize('edit_supports');
	}

	public function destroy($id)
	{
		$this->authorize('remove_chat_never');
		try {
			Support::withTrashed()
				->where('id', $id)
				->forceDelete();
			$description = " تم حزف الشات نهائيا رقم #" . $id;
			transaction("supports", $description);
			return returnData('', trans("global.success_deleted_chat_never"));
		} catch (\Exception $ex) {
			return returnData('', returnError($ex->getCode() . $ex->getMessage()));
		}
	}

	public function restore($id)
	{
		$this->authorize('restore_chat');
		Support::withTrashed()
			->where('id', $id)
			->restore();
		$description = " تم إسترجاع الشات رقم #" . $id;
		transaction("supports", $description);
		return returnData('', trans("global.success_restore_chat"));
	}

	public function done_read()
	{
		$this->authorize('show_supports');
		$supports = Support::where('done_read', 1)->get();
		return view('supports.support', compact('supports'));
	}

	public function un_read()
	{
		$this->authorize('show_supports');
		$supports = Support::where('done_read', 0)->get();
		return view('supports.support', compact('supports'));
	}

	public function done_contact()
	{
		$this->authorize('show_supports');
		$supports = Support::where('done_contact', 1)->get();
		return view('supports.support', compact('supports'));
	}

	public function un_contact()
	{
		$this->authorize('show_supports');
		$supports = Support::where('done_contact', 0)->get();
		return view('supports.support', compact('supports'));
	}

	public function done_problem()
	{
		$this->authorize('show_supports');
		$supports = Support::where('done_problem', 1)->get();
		return view('supports.support', compact('supports'));
	}

	public function un_problem()
	{
		$this->authorize('show_supports');
		$supports = Support::where('done_problem', 0)->get();
		return view('supports.support', compact('supports'));
	}

	public function done_deleted()
	{
		$this->authorize('show_supports');
		$supports = Support::onlyTrashed()->get();
		$deleted = 'deleted';
		return view('supports.support', compact('supports', 'deleted'));
	}
}
