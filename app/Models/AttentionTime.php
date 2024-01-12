<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttentionTime extends Model
{
    use HasFactory;

    protected $casts = [
        'hour_init' => 'date:hh:mm',
        'hour_end' => 'date:hh:mm',
        'hour_init_m' => 'date:hh:mm',
        'hour_end_m' => 'date:hh:mm',
        'hour_init_t' => 'date:hh:mm',
        'hour_end_t' => 'date:hh:mm'
    ];
}
