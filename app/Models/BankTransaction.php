<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankTransaction extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
      'statement',
      'amount',
      'bank_amount_after',
      'type',
      'bank_id',
      'user_id',
      'user_pay_id',
      'currency_id',
    ];
    public function currency()
    {
      return $this->belongsTo(Currency::class);
    }
    public function bank()
    {
      return $this->belongsTo(Bank::class);
    }
    public function user()
    {
      return $this->belongsTo(User::class);
    }
    public function userPay($id)
    {
      return User::find($id);
    }
}
