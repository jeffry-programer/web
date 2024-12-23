<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanContracting extends Model
{
    use HasFactory;

    public function plan(){
        return $this->belongsTo(Plan::class, 'plans_id');
    }
}
