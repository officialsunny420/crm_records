<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Transaction;

class Stocks extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_id', 'price', 'user_id','description','amount','date'];
    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    protected static function booted()
    {
        static::created(function ($stock) {
            // Create a new transaction record
            $transaction = Transaction::create([
                'amount' => ($stock->type == 1) ? $stock->amount  : $stock->price*$stock->qty,
                'user_id' => $stock->user_id,
                'module' => '1',
                'description' => 'Stock added', // You can customize the description
                'type' => 'debit', // Or 'income' depending on your application logic
            ]);

            // Update the stock's transaction_id with the newly created transaction's ID
            $stock->update(['transaction_id' => $transaction->id]);
        });
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
