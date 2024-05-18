<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'subject_id',
		'section_id',
		'currency_id',
		'teacher_id',
		'name',
		'price',
		'discount',
		'whatsapp',
		'telegram',
		'sub_price',
		'subscribers',
		'subscription_duration',
		'photo',
		'description_photo',
		'status',
		'type',
		'collectionID',
		'description_ar',
		'description_en',
	];

	public function scopeCourses($query)
	{
		if (auth()->user()->teacher_id === null) {
			return $query->with(["subject", "section", "teacher", "currency", "comments"])->orderBy('id', 'DESC');
		}
		return $query->with(["subject", "section", "teacher", "currency", "comments"])->where('teacher_id', auth()->user()->teacher_id)->orderBy('id', 'DESC');
	}

	public static function getOrdersCourse($id)
	{
		return Order::where("course_id", $id)->whereNull("lecture_id")->orderBy('id', 'DESC')->get();
	}

	public static function getCourseExamsUsers($id)
	{
		return Order::where("course_id", $id)->orderBy('id', 'DESC')->get();
	}

	public function section()
	{
		return $this->belongsTo(Section::class);
	}

	public function comments()
	{
		return $this->hasMany(Comment::class)->where('status', 1);
	}

	public function chapters()
	{
		return $this->hasMany(Chapter::class)->orderByRaw('CAST(`order` AS UNSIGNED) ASC');
	}

	public function lectures()
	{
		return $this->hasMany(Lecture::class);
	}

	public function offers()
	{
		return $this->belongsToMany(Offer::class);
	}


	public function teacher()
	{
		return $this->belongsTo(Teacher::class);
	}

	public function subject()
	{
		return $this->belongsTo(Subject::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function attachments()
	{
		return $this->morphMany(Attachment::class, 'attachable');
	}

	public function getStatus()
	{
		return $this->status == 1 ? 'مفعل' : 'غير مفعل';
	}
}
