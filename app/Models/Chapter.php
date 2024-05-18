<?php

namespace App\Models;

use App\Models\Lecture;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chapter extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name_ar',
		'name_en',
		'order',
		'course_id',
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function lectures()
	{
		return $this->hasMany(Lecture::class)->where("status", 1)->orderByRaw('CAST(`order` AS UNSIGNED) ASC');
	}
}
