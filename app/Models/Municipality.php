<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    public function state()
    {
        return $this->belongsTo(State::class, 'states_id', 'id');
    }

    public function stores()
    {
        return $this->hasMany(Municipality::class, 'municipalities_id');
    }

    public function sectors(){
        return $this->hasMany(Sector::class, 'id', 'municipalities_id');
    }
}
