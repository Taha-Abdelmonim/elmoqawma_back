<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Support extends Model
{
  use HasFactory, SoftDeletes;
  protected $table = "supports";
  protected $fillable = [
    "title",
    'message',
    'user_id',
    "done_read",
    "done_contact",
    "done_problem",
  ];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
