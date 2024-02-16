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

    public function typeStore(){
        return $this->belongsTo(TypeStore::class, 'type_stores_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'product_stores', 'stores_id', 'products_id');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'subscriptions', 'stores_id', 'users_id');
    }
}
