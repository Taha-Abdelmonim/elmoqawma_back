<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ask extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'title_ar',
		'title_en',
		'description_ar',
		'description_en',
	];


}
