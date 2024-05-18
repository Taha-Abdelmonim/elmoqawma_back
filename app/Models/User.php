<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements JWTSubject
{
	use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

	protected $fillable = [
		'name_ar',
		'name_en',
		'email',
		'password',
		'roles_name',
		'status',
		'phone',
		'phone_parent',
		'balance',
		'photo',
		'oauth_id',
		'oauth_type',
		'birth',
		'access_token',
		'teacher_id',
	];

	public function lectureViews()
	{
		return $this->hasMany(LectureView::class, "user_id");
	}

	public function latestScore($lectureID)
	{
		$count_exam = DetailExam::where("user_id", $this->id)->where("lecture_id", $lectureID)->get()->count();
		$DetailExam = DetailExam::where("user_id", $this->id)->where("lecture_id", $lectureID)->latest("id")->first();
		$info = "";
		$score = 0;
		if ($DetailExam) {
			$info = "<span class='text-success me-1'>$DetailExam->correct</span> / <span class='text-danger ms-1'>$DetailExam->mistake</span>";
			$score = $DetailExam->score;
		}
		return ["score" => $score, "info" => $info, "count_exam" => $count_exam];
	}

	public function courses($userID)
	{
		$coursesIDS = Course::Courses()->get()->pluck("id");
		return Order::where("user_id", $userID)->whereIn("course_id", $coursesIDS)->whereNull("lecture_id")->get();
	}

	public function lectures($userID)
	{
		$coursesIDS = Course::Courses()->get()->pluck("id");
		return Order::where("user_id", $userID)->whereIn("course_id", $coursesIDS)->whereNotNull("lecture_id")->get();
	}

	public function teacher()
	{
		return $this->belongsTo(Teacher::class);
	}

	protected $hidden = ['password', 'remember_token'];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'roles_name' => 'array',
	];

	public function name()
	{
		return $this["name_" . app()->getLocale()];
	}

	public function getJWTIdentifier()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims()
	{
		return [];
	}
}
