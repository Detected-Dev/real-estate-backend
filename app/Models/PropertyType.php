<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyTypesFactory> */
    use HasFactory;

    public function propertyies (){
        return $this->hasMany(Property::class);
    }
}
