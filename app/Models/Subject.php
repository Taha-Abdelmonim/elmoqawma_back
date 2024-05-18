<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Subject extends Model
{
	use HasFactory;

	protected $fillable = [
		'name_ar',
		'name_en',
		'photo',
	];

	public function teachers()
	{
		return $this->hasMany(TeacherSectionSubject::class);
	}

	public function sections()
	{
		return $this->hasMany(TeacherSectionSubject::class)->whereNotNull("section_id");
	}

	public function editSections()
	{
		return $this->hasMany(SubjectSection::class);
	}
}
