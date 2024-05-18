<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAnswer extends Model
{
	use HasFactory;

	protected $fillable = [
		'answer',
		'answer_type',
		'status',
		'bank_question_id',
	];

	public function question()
	{
		return $this->belongsTo(BankQuestion::class);
	}
}
