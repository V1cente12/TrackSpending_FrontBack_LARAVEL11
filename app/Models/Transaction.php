<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'type', 'amount', 'date', 'description', 'payment_method_id',
    ];
    protected $casts = [
        'date' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function expenses()
    {
        return $this->hasOne(Expense::class);
    }

    public function incomes()
    {
        return $this->hasOne(Income::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
