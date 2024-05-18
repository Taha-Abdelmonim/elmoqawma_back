<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpenseDetails extends Model
{
    use HasFactory,SoftDeletes;
  protected $fillable = [
    "description",
    "amount",
    "expense_id",
    "bank_id",
    "currency_id",
    "user_id",
  ];
  public function expense()
  {
    return $this->belongsTo(Expense::class);
  }
  public function bank()
  {
    return $this->belongsTo(Bank::class);
  }
  public function currency()
  {
    return $this->belongsTo(Currency::class);
  }
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
