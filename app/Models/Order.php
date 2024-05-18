<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'user_id',
		'course_id',
		'lecture_id',
		'offer_id',
		'type',
		'price',
		'bank_code',
		'code',
		'card_number',
		'card_type',
		'currency_id',
		'status',
		'code',
		'photo',
	];

	public function scopeOrders($query)
	{
		if (auth()->user()->teacher_id === null) {
			return $query;
		}
		$coursesIDS = Course::Courses()->get()->pluck("id");
		return $query->whereIn('course_id', $coursesIDS);
	}

	public function lectureView()
	{
		return $this->belongsTo(LectureView::class, "id", "order_id");
	}

	public function course()
	{
		return $this->belongsTo(Course::class);
	}

	public function lecture()
	{
		return $this->belongsTo(Lecture::class);
	}

	public function offer()
	{
		return $this->belongsTo(Offer::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

	public function getStatus()
	{
		return $this->status == 1 ? 'مفعل' : 'غير مفعل';
	}
}
