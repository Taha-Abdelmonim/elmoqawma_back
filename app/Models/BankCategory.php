<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCategory extends Model
{
	use HasFactory;

	protected $fillable = [
		'name',
	];

	public function questions()
	{
		return $this->hasMany(BankQuestion::class)->orderBy("order");
	}

	public function countQuestions()
	{
		return $this->questions()->count();
	}
}
