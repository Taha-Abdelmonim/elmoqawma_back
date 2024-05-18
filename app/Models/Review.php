<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Review extends Model
{
    use HasFactory, SoftDeletes;
  protected $fillable = [
    "comment",
    'status',
    'user_id',
    'section_id',
  ];
  public function section()
  {
    return $this->belongsTo(Section::class);
  }
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
