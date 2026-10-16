<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyRequest extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyRequestsFactory> */
    use HasFactory;

    protected $fillable = [
        'property_id' ,
        'user_id',
        'agency_id' ,
        'type' ,
        'message',
        'offer_price' ,
        'status'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function property(){
        return $this->belongsTo(Property::class);
    }
    public function visits () {
        return $this->hasMany(PropertyVisit::class);
    }

}
