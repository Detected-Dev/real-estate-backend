<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    /** @use HasFactory<\Database\Factories\AgenciesFactory> */
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'address',
        'city' ,
        'telephone' ,
        'email',
        'logo',
    ];

    public function properties(){
        return $this->hasMany(Property::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
