<?php

namespace App\Http\Controllers;

use App\Imports\QuestionsImport;
use App\Models\BankAnswer;
use App\Models\BankCategory;
use App\Models\BankQuestion;
use App\Http\Requests\QuestionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class BankQuestionController extends Controller
{
//	SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'videoID' cannot be null (SQL: insert into `lectures` (`type_video`, `type`, `title`, `order`, `price`, `re_exam_count`, `count_questions`, `duration_exam`, `duration`, `start_time`, `videoID`, `chapter_id`, `course_id`, `updated_at`, `created_at`) values (youtube, 1, 333, 3, 33, 3, 3, 3, ?, 2023-12-16 01:30:00, ?, 1, 1, 2023-12-16 01:30:42, 2023-12-16 01:30:42))
	public function categoryGetChild(Request $request)
	{

		$bank_category = BankCategory::find($request->id);
		$questions = $bank_category->questions;
		if ($questions->isNotEmpty()) {
			return returnData("" . view("questions.child_category", compact("questions")));
		}
		return "";
	}

	public function categories()
	{
		$categories = BankCategory::orderBy("id", "ASC")->get();
		return view("bank_questions.categories", compact("categories"));
	}

	public function index($bank_category_id)
	{
		$this->authorize("show_bank_questions");
		$category = BankCategory::find($bank_category_id);
		$questions = BankQuestion::where("bank_category_id", $bank_category_id)->orderBy("id", "ASC")->get();
		return view("bank_questions.index", compact("questions", "bank_category_id", "category"));
	}

	public function create($bank_category_id)
	{
		$this->authorize("create_bank_questions");
		$category = BankCategory::find($bank_category_id);
		return view("bank_questions.create", compact("bank_category_id", "category"));
	}

	public function QuestionsImport($bank_category_id, Request $request)
	{
		Excel::import(new QuestionsImport($bank_category_id), $request->file('file'));
		$questions = BankQuestion::where("bank_category_id", $bank_category_id)->orderBy("id", "ASC")->get();
		return view("bank_questions.import_questions", compact("questions", "bank_category_id"));
	}

	public function store($bank_category_id, QuestionRequest $request)
	{
		$this->authorize("create_bank_questions");
		$data = $request->all();
		$data["file"] = $request->hasFile("file") ? $request->file->store("bank_questions", "public_image") : null;
		$data["bank_category_id"] = $bank_category_id;
		$question = BankQuestion::create($data);
		if ($request->has('answers')) {
			foreach ($request->answers as $answer) {
				BankAnswer::create([
					"answer" => $answer["answer_type"] === "image" ? $answer["answer"]->store("bank_answers", "public_image") : $answer["answer"],
					"answer_type" => $answer["answer_type"],
					"status" => $answer["status"],
					"bank_question_id" => $question->id,
				]);
			}
		}
		$description = " تم إنشاء سؤال " . $question->question_ar;
		transaction("bank_questions", $description);
		return redirect()->route("bank_questions", $bank_category_id)->with("success", trans("global.success_create"));
	}


	public function edit($bank_category_id, $id)
	{
		$this->authorize("edit_bank_questions");
		$question = BankQuestion::findorFail($id);
		$answers = $question->answers;
		$bank_categories = BankCategory::orderBy("id", "ASC")->get();
		$category = BankCategory::find($bank_category_id);
		return view("bank_questions.edit", compact("question", "answers", "bank_categories", "bank_category_id", "category"));
	}

	public function update($bank_category_id, $id, QuestionRequest $request)
	{
		$this->authorize("edit_bank_questions");
		$question = BankQuestion::findorFail($id);
		$data = $request->all();
		if ($request->hasFile("file")) {
			if ($question->type !== "text") {
				Storage::disk("public_image")->delete($question->file);
			}
			$data["file"] = $request->file("file")->store("bank_questions", "public_image");
		}
		$question->update($data);
		if ($request->delete_answers != "[]") {
			$answers = BankAnswer::whereIn("id", json_decode($request->delete_answers))->get();
			foreach ($answers as $answer) {
				if ($answer->answer_type === "image") {
					Storage::disk("public_image")->delete($answer->answer);
				}
				$answer->delete();
			}
		}
		if ($request->has('answers')) {
			foreach ($request->answers as $answer) {
				BankAnswer::create([
					"answer" => $answer["answer_type"] === "imageNew" ? $answer["answer"]->store("bank_answers", "public_image") : $answer["answer"],
					"answer_type" => $answer["answer_type"] === "imageNew" || $answer["answer_type"] === "image" ? "image" : "text",
					"status" => $answer["status"],
					"bank_question_id" => $question->id,
				]);
			}
		}
		$description = " تم تعديل سؤال " . $question->question_ar;
		transaction("bank_questions", $description);
		return redirect()->route("bank_questions", $bank_category_id)->with("success", trans("global.success_update"));
	}

	public function destroy($bank_category_id, $id)
	{
		$this->authorize("remove_bank_questions");
		$question = BankQuestion::findorFail($id);
		if ($question->type !== "text") {
			Storage::disk("public_image")->delete($question->file);
		}
		$answers = BankAnswer::where("bank_question_id", $id)->get();
		foreach ($answers as $answer) {
			if ($answer->answer_type === "image") {
				Storage::disk("public_image")->delete($answer->answer);
			}
			$answer->delete();
		}
		$description = " تم حزف سؤال " . $question->question_ar;
		transaction("bank_questions", $description);
		$question->delete();
		return redirect()->route("bank_questions", $bank_category_id)->with("success", trans("global.success_delete"));
	}

	public function destroyAll($bank_category_id, Request $request)
	{
		$this->authorize("remove_bank_questions");
		$questions = BankQuestion::whereIn("id", json_decode($request->ids))->get();
		$description = " تم حزف الاسئلة (";
		foreach ($questions as $question) {
			$answers = BankAnswer::where("bank_question_id", $question->id)->get();
			foreach ($answers as $answer) {
				if ($answer->answer_type === "image") {
					Storage::disk("public_image")->delete($answer->answer);
				}
				$answer->delete();
			}
			$description .= ", " . $question->question_ar . " ";
			if ($question->type !== "text") {
				Storage::disk("public_image")->delete($question->file);
			}
			$question->delete();
		}
		$description .= " )";
		transaction("bank_questions", $description);
		return redirect()->route("bank_questions", $bank_category_id)->with("success", trans("global.success_delete_all"));
	}
}
