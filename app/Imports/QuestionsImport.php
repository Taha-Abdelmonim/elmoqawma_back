<?php

namespace App\Imports;

use App\Models\BankAnswer;
use App\Models\BankQuestion;
use Maatwebsite\Excel\Concerns\ToModel;

use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class QuestionsImport implements ToModel, WithStartRow, WithValidation, WithHeadingRow
{
	public $bank_category_id;
	public $answers = [];

	public function __construct($bank_category_id)
	{
		$this->bank_category_id = $bank_category_id;
	}

	public function model(array $row)
	{
//		dd($this->bank_category_id);
		$BankQuestion = BankQuestion::create([
			'order' => $row["order"],
			'question_ar' => $row["question_ar"],
			'question_en' => $row["question_en"],
			'Justify' => $row["justify"],
			"file" => null,
			"type" => "text",
			"bank_category_id" => $this->bank_category_id,
		]);
		$answers = collect($row)->filter(function ($value, $key) {
			return (is_int($key) || $key === "answers") && $value !== null;
		})->values()->toArray();
		foreach ($answers as $key => $answer) {
			BankAnswer::create([
				"answer" => $answer,
				"answer_type" => "text",
				"status" => $key === 0 ? 1 : 0,
				"bank_question_id" => $BankQuestion->id,
			]);
		}
	}


	public function startRow(): int
	{
		return 2;
	}


	public function rules(): array
	{
		return [
//			'question_ar' => 'required',
//			'question_en' => 'required',
//			'Justify' => 'required',
		];
	}


}
