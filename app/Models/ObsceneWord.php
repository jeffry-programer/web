<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObsceneWord extends Model
{
    use HasFactory;

    public $table = 'obscene_words';

    protected $fillable = ['word', 'created_at'];
}
