<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lecture extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'title',
		'exam_google',
		'type',
		'order',
		'lecture_views',
		'type_video',
		'videoID',
		'price',
		're_exam_count',
		'count_questions',
		'duration_exam',
		'status',
		'course_id',
		'chapter_id',

		'views',
		'duration',
		'start_time',
		'start_url',
		'join_url',
	];

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function chapter()
	{
		return $this->belongsTo(Chapter::class);
	}

	public function deleteLectureUser($id)
	{
		LectureUser::where("lecture_id", $id)->delete();
	}

	public function attachments()
	{
		return $this->morphMany(Attachment::class, 'attachable');
	}

	public function getType()
	{
		return $this->type == 1 ? trans("global.paid") : trans("global.free");
	}
}
