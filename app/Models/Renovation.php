<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Renovation extends Model
{
    use HasFactory;

    protected $fillable = ['stores_id', 'plans_id' ,'image', 'comentary', 'status'];
}
