<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Section extends Model
{
	use HasFactory;

	protected $fillable = [
		'name_ar',
		'name_en',
		'description_ar',
		'description_en',
		'photo',
	];

	public function teachers()
	{
		return $this->hasMany(TeacherSectionSubject::class);
	}

	public function subjects()
	{
		return $this->hasMany(SubjectSection::class);
	}

	public function courses()
	{
		return $this->hasMany(Course::class)->with('teacher', 'subject', 'currency', 'comments');
	}

	public function section($id)
	{
		return Section::find($id) != null ? Section::find($id)['name_' . App::getLocale()] : trans("global.main_section");
	}
}
