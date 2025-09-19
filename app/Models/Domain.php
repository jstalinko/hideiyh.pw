<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;

    protected $fillable = 
    ['signature',
    'user_id','domain','ip_server'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
