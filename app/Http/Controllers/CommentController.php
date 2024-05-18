<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use Illuminate\Http\Request;

class CommentController extends Controller
{
	public function getComments($type = null, $id = null)
	{
		$coursesIDS = Course::Courses()->get()->pluck("id");
		$comments = Comment::with(["user", "course"])->whereIn("course_id", $coursesIDS)->orderBy("id", "DESC")->get();
		if ($id != null && $id != "0") {
			$comments = Comment::with(["course" => ["subject"], "user"])->where($type . "_id", $id)
				->orderBy("id", "DESC")
				->get();
		}
		// dd($comments);
		return response(["data" => $comments]);
	}

	public function index($type)
	{
		$this->authorize("show_comments");
		return view("comments.comments", compact("type"));
	}

	public function create()
	{
		$this->authorize("create_comments");
	}

	public function store(Request $request)
	{
		$this->authorize("create_comments");
	}

	public function show($id, Comment $coment)
	{
		$this->authorize("show_comments");
		$comment = Comment::findorFail($id);
		return view("comments.show", compact("comment"));
	}

	public function toggleStatus($id, Request $request)
	{
		$this->authorize("comment_toggle_status");
		if ($request->ajax() and isset($request->status)) {
			$msg = $request->status == 1 ? trans("global.success_enabled") : trans("global.success_not_enabled");
			$comment = Comment::findorFail($id);
			$comment->update([
				"status" => $request->status,
			]);
			$user = $comment->user->name();
			$description = $msg . " التعليق " . " لمستخدم " . $user;
			transaction("comments", $description);
			return returnData($comment, $msg);
		}
		abort("301", "unAuthenticated");
	}

	public function edit(Comment $coment)
	{
		$this->authorize("edit_comments");
	}

	public function update(Request $request, Comment $coment)
	{
		$this->authorize("edit_comments");
	}

	public function destroy($id)
	{
		$this->authorize("remove_comments");
		$comment = Comment::findorFail($id);
		$user = $comment->user->name();
		$description = " تم حزف التعليق " . " لمستخدم " . $user;
		transaction("comments", $description);
		$comment->delete();
		session()->put("success", trans("global.success_delete"));
		return redirect()->route("comments");
	}
}
