<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankQuestion extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'question_ar',
		'question_en',
		'Justify',
		'order',
		'file',
		'type',
		'bank_category_id',
	];

	public function category()
	{
		return $this->hasMany(BankCategory::class);
	}

	public function answers()
	{
		return $this->hasMany(BankAnswer::class);
	}

}
