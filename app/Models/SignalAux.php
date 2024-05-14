<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalAux extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'stores_id', 'detail', 'created_at'];

    public $table = 'signals_aux';
}
