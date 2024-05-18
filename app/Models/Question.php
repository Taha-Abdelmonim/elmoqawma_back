<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'question_ar',
		'question_en',
		'Justify',
		'file',
		'type',
		'related',
		'course_id',
		'lecture_id',
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function lecture()
	{
		return $this->belongsTo(Lecture::class);
	}

	public function answers()
	{
		return $this->hasMany(Answer::class);
	}

	public function answersRandom()
	{
		return $this->hasMany(Answer::class)->inRandomOrder();
	}
}
