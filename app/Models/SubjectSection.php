<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubjectSection extends Model
{
	use HasFactory;

	protected $fillable = [
		'section_id',
		'subject_id',
	];

	public function section()
	{
		return $this->belongsTo(Section::class);
	}

	public function subjects()
	{
		return $this->belongsTo(Subject::class);
	}

}
