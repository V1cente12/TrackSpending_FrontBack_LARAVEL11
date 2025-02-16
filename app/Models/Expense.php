<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
