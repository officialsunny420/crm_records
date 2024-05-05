<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_id', 'amount', 'user_id'];
    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    protected static function booted()
    {
        static::created(function ($entry) {
            // Create a new transaction record
            $transaction = Transaction::create([
                'amount' => $entry->amount,
                'module' => '2',
                'user_id' => $entry->user_id,
                'description' => 'Entry added', // You can customize the description
                'type' => 'debit', // Or 'income' depending on your application logic
            ]);

            // Update the entry's transaction_id with the newly created transaction's ID
            $entry->update(['transaction_id' => $transaction->id]);
        });
    }

    public function transaction()
    {
        return $this->belongsTo('App\Transaction');
    }
}
