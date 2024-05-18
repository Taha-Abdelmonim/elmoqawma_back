<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LectureUser extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'user_id',
		'course_id',
		'lecture_id',
		'status',

	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function lecture()
	{
		return $this->belongsTo(Lecture::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
