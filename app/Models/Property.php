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
        'latitude',
        'longitude',
    ];

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }
    public function propertyType(){
        return $this->belongsTo(PropertyType::class);
    }
    public function agency (){
        return $this->belongsTo(Agency::class);
    }
    public function visits (){
        return $this->hasMany(PropertyVisit::class);
    }
}
