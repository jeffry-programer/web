<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignalAux extends Model
{
    use HasFactory;

    protected $fillable = ['users_id', 'stores_id', 'status', 'status2', 'read', 'detail', 'created_at', 'confirmation'];

    public $table = 'signals_aux';

    public function store()
    {
        return $this->belongsTo(Store::class, 'stores_id', 'users_id');
    }
}
