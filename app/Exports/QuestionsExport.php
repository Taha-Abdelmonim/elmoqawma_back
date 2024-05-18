<?php

namespace App\Exports;

use App\Models\BankQuestion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class QuestionsExport implements FromCollection
{
	protected $questions;

	public function __construct(Collection $questions)
	{
		$this->questions = $questions;
	}

	public function collection()
	{
		$data = $this->questions->prepend([
			'#',
			'question_ar',
			'question_en',
			'Justify',
			'related',
		]);
		return $data;
	}
}