<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    public function promotions(){
        return $this->hasMany(Promotion::class, 'stores_id');
    }
}
