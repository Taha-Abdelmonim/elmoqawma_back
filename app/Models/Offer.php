<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name_ar',
		'name_en',
		'price',
		'photo',
		'subscribers',
		'stars',
		'duration',
		'description_ar',
		'description_en',
		'currency_id',
	];

	public function courses()
	{
		return $this->belongsToMany(Course::class);
	}


	public function currency()
	{
		return $this->belongsTo(Currency::class);
	}

}
