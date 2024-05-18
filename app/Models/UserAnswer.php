<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAnswer extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'detail_exam_id',
		'question_id',
		'answer_id',
		'status',
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function answers()
	{
		return $this->hasMany(Answer::class);
	}
}
