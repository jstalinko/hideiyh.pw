<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bugreport extends Model
{
    use HasFactory;

    protected $fillable = [
            'title',
            'content',
            'image',
            'status',
            'active', 
            'user_id'
    ];
}
