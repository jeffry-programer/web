<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'municipalities_id', 'id');
    }

    public function sectors(){
        return $this->hasMany(Sector::class, 'cities_id', 'id');
    }
}
