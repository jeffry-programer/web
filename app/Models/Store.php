<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['users_id','type_stores_id','cities_id','name','description','email','address','RIF','phone','image','image2','link','status','score_store'];

    public function promotions(){
        return $this->hasMany(Promotion::class, 'stores_id');
    }

    public function city(){
        return $this->belongsTo(City::class, 'cities_id', 'id');
    }
}
