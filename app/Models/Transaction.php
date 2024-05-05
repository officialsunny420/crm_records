<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Stocks;

class Transaction extends Model
{
    protected $fillable = ['amount','description','module','type','user_id','transaction_id'];
    use HasFactory;
    public function scopeTransactionsOnly($query)
    {
        return $query->where('type', 'transaction');
    }
    public function stocks()
    {
        return $this->hasOne(Stocks::class, 'transaction_id', 'id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
