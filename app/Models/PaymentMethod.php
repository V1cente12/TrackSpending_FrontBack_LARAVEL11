<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'icon',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public static function getPaymentMethodTypes()
    {
        return ['card', 'cash', 'qr'];
    }
}
