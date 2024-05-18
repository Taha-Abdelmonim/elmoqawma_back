<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherSectionSubject extends Model
{
	use HasFactory;

	protected $fillable = [
		'teacher_id',
		'section_id',
		'subject_id',
	];

	public function teacher()
	{
		return $this->belongsTo(Teacher::class);
	}

	public function section()
	{
		return $this->belongsTo(Section::class);
	}

	public function subjects()
	{
		return $this->belongsTo(Subject::class);
	}

}
