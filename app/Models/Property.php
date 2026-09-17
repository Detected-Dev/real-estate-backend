<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'agency_id',
        'property_type_id',
        'title',
        'description',
        'transaction_type',
        'price',
        'address',
        'city',
        'postal_code',
        'surface',
        'bedrooms',
        'bathrooms',
        'floors',
        'status',
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
}
