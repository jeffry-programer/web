<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['users_id', 'stores_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'stores_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'conversations_id');
    }
}
