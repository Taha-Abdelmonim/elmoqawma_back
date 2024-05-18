<?php

namespace App\Http\Controllers;

use App\Models\BankAnswer;
use App\Models\BankCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BankCategoryController extends Controller
{

	public function index(Request $request)
	{
		$this->authorize('show_bank_categories');
		if ($request->search) {
			$bankCategories = BankCategory::where('name', 'like', $request->search)->get();
		} else {
			$bankCategories = BankCategory::get();
		}
		return view('bank_categories.index', compact('bankCategories'));
	}

	public function create()
	{
		$this->authorize('create_bank_categories');
		return view('bank_categories.create');
	}

	public function store(Request $request)
	{
		$this->authorize('create_bank_categories');
		$this->validate($request,
			['name' => 'required|unique:bank_categories,name'],
			['name.required' => trans("validation.name_required"), 'name.unique' => trans("validation.name_unique"),]
		);
		$bankCategory = BankCategory::create([
			'name' => $request->name,
		]);
		return redirect()->route('bank_categories_index')->with('success', 'تم اضافة قسم بنجاح');
	}


	public function edit($id)
	{
		$this->authorize('edit_bank_categories');

		$bankCategory = BankCategory::find($id);
		return view('bank_categories.edit', compact('bankCategory'));

	}

	public function update($id, Request $request)
	{

		$this->authorize('edit_bank_categories');
		$this->validate($request,
			['name' => 'required'],
			['name.required' => trans("validation.name_required")]
		);
		$category = BankCategory::find($id);
		$category->update([
			'name' => $request->name,
		]);
		return redirect()->route('bank_categories_index')->with('success', __('global.success_update'));
	}

	public function destroy($id)
	{
		$this->authorize('remove_bank_categories');
		$category = BankCategory::find($id);
		foreach ($category->questions as $question) {
			if ($question->type !== "text") {
				Storage::disk("public_image")->delete($question->file);
			}
			$answers = BankAnswer::where("bank_question_id", $question->id)->get();
			foreach ($answers as $answer) {
				if ($answer->answer_type === "image") {
					Storage::disk("public_image")->delete($answer->answer);
				}
				$answer->delete();
			}
			$question->delete();
		}
		$category->delete();
		return redirect()->route('bank_categories_index',)->with('success', 'تم حذف قسم بنجاح');
	}

}
