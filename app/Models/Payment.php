<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'transaction_id',
        'amount',
        'payment_method',
        'status',
        'paid_at',
        'transaction_reference',
    ];
}
