<?php

namespace App\Http\Controllers;

use App\Exports\QuestionsExport;
use App\Models\AmountBank;
use App\Models\Answer;
use App\Models\BankCategory;
use App\Models\BankQuestion;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\DetailExam;
use App\Models\Lecture;
use App\Models\Question;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionRequest;
use App\Models\Currency;
use App\Models\User;
use App\Models\UserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
	public function exportExel($courseID, $lectureID)
	{
		$questionsWithAnswers = Question::where("course_id", $courseID)
			->where("lecture_id", $lectureID)
			->orderBy("id", "ASC")
			->with('answers')
			->get(['id', 'question_ar', 'question_en', 'Justify', 'related']);

		$data = collect([]);

		foreach ($questionsWithAnswers as $question) {
			$rowData = [
				'#' => $question->id,
				'question_ar' => strip_tags($question->question_ar),
				'question_en' => strip_tags($question->question_en),
				'Justify' => $question->Justify,
				'related' => $question->related,
			];
			foreach ($question->answers as $answer) {
				$rowData[] = $answer->answer;
			}

			$data->push($rowData);
		}
		return Excel::download(new QuestionsExport($data), 'questions.xlsx');
	}

	public function courses()
	{
		$this->authorize("show_questions");
		$courses = Course::Courses()->get();
		foreach ($courses as $course) {
			$course->lectures_count = Lecture::where("course_id", $course->id)->get()->count();
		}
		return view("questions.courses", compact("courses"));
	}

	public function lectures($courseID)
	{
		$course = Course::find($courseID);
		$lectures = Lecture::where("course_id", $courseID)->orderBy('id', 'ASC')->get();
		foreach ($lectures as $lecture) {
			$lecture->question_count = Question::where("course_id", $courseID)->where("lecture_id", $lecture->id)->count();
		}
		return view("questions.lectures", compact("course", "lectures"));
	}

	public function index($courseID, $lectureID)
	{
		$this->authorize("show_questions");
		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		$questions = Question::where("course_id", $courseID)->where("lecture_id", $lectureID)->orderBy("id", "ASC")->get();
		return view("questions.index", compact("course", "lecture", "questions"));
	}

	public function bankCreate($courseID, $lectureID)
	{
		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		$bank_categories = BankCategory::orderBy("id", "ASC")->get();
		return view("questions.bank_create", compact("course", "lecture", "bank_categories"));
	}

	public function bankSave($courseID, $lectureID, Request $request)
	{
		$this->validate(
			$request,
			['bank_category_id' => 'required', 'related' => 'required'],
			['bank_category_id.required' => trans("validation.bank_category_id_required")]
		);
		function create($questions, $courseID, $lectureID, $related)
		{
			$questions = $questions->map(function ($question) use ($courseID, $lectureID, $related) {
				$question->course_id = $courseID;
				$question->lecture_id = $lectureID;
				$question->related = $related;
				return $question;
			});
			foreach ($questions as $question) {
				if ($question->type !== "text") {
					$file = public_path("images/$question->file");
					$pathCopy = public_path("images/questions");
					shell_exec("cp -r $file $pathCopy");
					$question->file = str_replace("bank_questions", "questions", $question->file);
				}
				$questionCreate = Question::create($question->toArray());
				foreach ($question->answers as $answer) {
					$answer->question_id = $questionCreate->id;
					if ($answer->answer_type === "image") {
						$file = public_path("images/$answer->answer");
						$pathCopy = public_path("images/answers");
						shell_exec("cp -r $file $pathCopy");
						$answer->answer = str_replace("bank_answers", "answers", $answer->answer);
					}
					Answer::create($answer->toArray());
				}
			}
		}


		if ($request->has("bank_category_id") && !$request->has("bank_question_id")) {
			$bank_category = BankCategory::find($request->bank_category_id);
			create($bank_category->questions, $courseID, $lectureID, $request->related);
		} else if ($request->has("bank_question_id")) {
			$questions = BankQuestion::with("answers")->whereIn("id", $request->bank_question_id)->get();
			create($questions, $courseID, $lectureID, $request->related);
		}
		return redirect()->route("questions", [$courseID, $lectureID])->with("success", trans("global.success_create"));
	}

	public function create($courseID, $lectureID)
	{
		$this->authorize("create_questions");
		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		return view("questions.create", compact("course", "lecture"));
	}

	public function store($courseID, $lectureID, QuestionRequest $request)
	{
		$this->authorize("create_questions");
		$request->merge(['course_id' => $courseID, 'lecture_id' => $lectureID]);
		$data = $request->all();
		$data["file"] = $request->hasFile("file") ? $request->file->store("questions", "public_image") : null;
		$question = Question::create($data);
		if ($request->has('answers')) {
			foreach ($request->answers as $answer) {
				Answer::create([
					"answer" => $answer["answer_type"] === "image" ? $answer["answer"]->store("answers", "public_image") : $answer["answer"],
					"answer_type" => $answer["answer_type"],
					"status" => $answer["status"],
					"question_id" => $question->id,
				]);
			}
		}
		$description = " تم إنشاء سؤال " . $question->question_ar;
		transaction("questions", $description);
		return redirect()->route("questions", [$courseID, $lectureID])->with("success", trans("global.success_create"));
	}


	public function edit($courseID, $lectureID, $id)
	{
		$this->authorize("edit_questions");
		$question = Question::findorFail($id);
		$answers = $question->answers;
		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		return view("questions.edit", compact("question", "answers", "course", "lecture"));
	}

	public function update($courseID, $lectureID, $id, QuestionRequest $request)
	{

		$this->authorize("edit_questions");
		$question = Question::findorFail($id);
		$data = $request->all();
		$data["course_id"] = $courseID;
		if ($request->hasFile("file")) {
			if ($question->type !== "text") {
				Storage::disk("public_image")->delete($question->file);
			}
			$data["file"] = $request->file("file")->store("questions", "public_image");
		}
		$question->update($data);
		if ($request->delete_answers != "[]") {
			$answers = Answer::whereIn("id", json_decode($request->delete_answers))->get();
			foreach ($answers as $answer) {
				if ($answer->answer_type === "image") {
					Storage::disk("public_image")->delete($answer->answer);
				}
				$answer->delete();
			}
		}
		if ($request->has('answers')) {
			foreach ($request->answers as $answer) {
				Answer::create([
					"answer" => $answer["answer_type"] === "imageNew" ? $answer["answer"]->store("answers", "public_image") : $answer["answer"],
					"answer_type" => $answer["answer_type"] === "imageNew" || $answer["answer_type"] === "image" ? "image" : "text",
					"status" => $answer["status"],
					"question_id" => $question->id,
				]);
			}
		}
		$description = " تم تعديل سؤال " . $question->question_ar;
		transaction("questions", $description);
		return redirect()->route("questions", [$courseID, $lectureID])->with("success", trans("global.success_update"));
	}

	public function destroy($courseID, $lectureID, $id)
	{
		$this->authorize("remove_questions");
		$question = Question::findorFail($id);
		if ($question->type !== "text") {
			Storage::disk("public_image")->delete($question->file);
		}
		$answers = Answer::where("question_id", $id)->get();
		foreach ($answers as $answer) {
			if ($answer->answer_type === "image") {
				Storage::disk("public_image")->delete($answer->answer);
			}
			$answer->delete();
		}
		$description = " تم حزف سؤال " . $question->question_ar;
		transaction("questions", $description);
		$question->delete();
		return redirect()->route("questions", [$courseID, $lectureID])->with("success", trans("global.success_delete"));
	}

	public function destroyAll($courseID, $lectureID, Request $request)
	{
		$this->authorize("remove_questions");
		$questions = Question::whereIn("id", json_decode($request->ids))->get();
		$description = " تم حزف الاسئلة (";
		foreach ($questions as $question) {
			$answers = Answer::where("question_id", $question->id)->get();
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
		transaction("questions", $description);
		return redirect()->route("questions", [$courseID, $lectureID])->with("success", trans("global.success_delete_all"));
	}


	public function coursesExams()
	{
		$courses = Course::Courses()->get();
		foreach ($courses as $course) {
			$course->orders_count = $course->getCourseExamsUsers($course->id)->count();
		}

		return view("questions.exam_courses", compact("courses"));
	}

	public function coursesExamsLectures($courseID)
	{
		$course = Course::find($courseID);
		$lectures = Lecture::where("course_id", $courseID)->orderBy('id', 'ASC')->get();
		foreach ($lectures as $lecture) {
			$lecture->question_count = Question::where("lecture_id", $lecture->id)->whereNull("deleted_at")->get()->count();
		}

		return view("questions.exam_courses_lectures", compact("course", "lectures"));
	}

	public function coursesExamsUsers($courseID, $lectureID)
	{

		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		$userIDS = DetailExam::where("course_id", $courseID)->where("lecture_id", $lectureID)->pluck("user_id");
		$users = User::whereIn("id", $userIDS)->get();
		foreach ($users as $user) {
			$user->count_exam_user = DetailExam::where("user_id", $user->id)->where("course_id", $courseID)->where("lecture_id", $lectureID)->get()->count();
			$DetailExam = DetailExam::where("user_id", $user->id)->where("course_id", $courseID)->where("lecture_id", $lectureID)->latest("id")->first();
			$user->score_exam_user = $DetailExam->score;
			$user->info_exam_user = "<span class='text-success me-1'>$DetailExam->correct</span> / <span class='text-danger ms-1'>$DetailExam->mistake</span>";
		}
		return view("questions.exam_courses_users", compact("users", "course", "lecture"));
	}

	public function recordedExams($courseID, $lectureID, $userID)
	{

		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		$user = User::find($userID);
		$detailExams = DetailExam::where("user_id", $userID)->where("course_id", $courseID)->where("lecture_id", $lectureID)->get();
		return view("questions.recorded_exams", compact("detailExams", "course", "lecture", "user"));
	}

	public function recordedShow($courseID, $lectureID, $userID, $examID)
	{
		$course = Course::find($courseID);
		$lecture = Lecture::find($lectureID);
		$user = User::find($userID);
		$userAnswers = UserAnswer::where("detail_exam_id", $examID)->get();
		$answersIDS = $userAnswers->pluck("answer_id")->toArray();
		$questions = Question::with("answers")->whereIn("id", $userAnswers->pluck("question_id"))->get();
		return view("questions.recorded_show", compact("course", "lecture", "user", "answersIDS", "questions", "examID"));
	}
}
