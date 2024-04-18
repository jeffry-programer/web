<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    public function country()
    {
        return $this->belongsTo(Country::class, 'countries_id');
    }

    public function municipalities()
    {
        return $this->hasMany(Municipality::class, 'states_id');
    }
}
