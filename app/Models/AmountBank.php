<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AmountBank extends Model
{
    use HasFactory;
  protected $fillable = [
    'amount',
    'bank_id',
    'currency_id',
  ];
  public function currency()
  {
    return $this->belongsTo(Currency::class);
  }
  public function bank()
  {
    return $this->belongsTo(bank::class);
  }
}
