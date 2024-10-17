<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    public $table = 'comments_services';

    protected $fillable = ['users_id','stores_id','comment','status','created_at'];

    public function user(){
        return $this->belongsTo(User::class, 'users_id');
    }
}
