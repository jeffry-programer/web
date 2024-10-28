<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['users_id','type_stores_id','sectors_id','municipalities_id','name','description','email','address','RIF','phone','image','image2','link','status','score_store','capacidad','tipo','dimensiones','categories_stores_id'];

    public function promotions(){
        return $this->hasMany(Promotion::class, 'stores_id');
    }

    public function publicities(){
        return $this->hasMany(Publicity::class, 'stores_id');
    }

    public function subscriptions(){
        return $this->hasMany(Subscription::class, 'stores_id');
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

    public function user(){
        return $this->belongsTo(User::class, 'users_id');
    }

    // Relación con los planes contratados de la tienda
    public function planContrating(){
        return $this->belongsTo(PlanContracting::class, 'id', 'stores_id');
    }

    public function municipality(){
        return $this->belongsTo(Municipality::class, 'municipalities_id');
    }

    public function sector(){
        return $this->belongsTo(Sector::class, 'sectors_id');
    }
}
