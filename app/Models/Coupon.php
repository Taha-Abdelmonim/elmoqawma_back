<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'code',
		'discount',
		'course_id',
		'lecture_id',
		'type',
		'offer_id',
		'user_id',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
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


}
