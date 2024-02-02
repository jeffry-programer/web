<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    public function state(){
        return $this->BelongsTo(State::class, 'states_id');
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'cities_id');
    }
}
