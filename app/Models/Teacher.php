<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
	use HasFactory, SoftDeletes;

	protected $fillable = [
		'name_ar',
		'name_en',
		'description_ar',
		'description_en',
		'email',
		'phone',
		'photo',
	];

	public function sections()
	{
		return $this->hasMany(TeacherSectionSubject::class)->whereNotNull("section_id");
	}

	public function subjects()
	{
		return $this->hasMany(TeacherSectionSubject::class)->whereNotNull("subject_id");
	}


}
