<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailExam extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'course_id',
		'lecture_id',
		'user_id',
		'score',
		'correct',
		'mistake',
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function answers()
	{
		return $this->hasMany(Answer::class);
	}

	public function userAnswers()
	{
		return $this->hasMany(UserAnswer::class);
	}
}
