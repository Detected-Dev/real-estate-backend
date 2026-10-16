<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /** @use HasFactory<\Database\Factories\TransactionsFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id',
        'seller_id',
        'buyer_id',
        'agency_id',
        'type',
        'amount',
        'transaction_date',
    ];
}
