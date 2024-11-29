<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renovation extends Model
{
    use HasFactory;

    protected $fillable = ['stores_id', 'plans_id' ,'image', 'comentary', 'status', 'comment_admin', 'status_renovation'];

    public function plan(){
        return $this->belongsTo(Plan::class, 'plans_id');
    }

    public function store(){
        return $this->belongsTo(Store::class, 'stores_id');
    }
}
