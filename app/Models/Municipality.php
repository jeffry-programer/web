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

    public function cities()
    {
        return $this->hasMany(City::class, 'cities_id');
    }
}
