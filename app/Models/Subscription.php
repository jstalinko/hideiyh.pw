<?php

namespace App\Models;

use App\Models\Package;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'status',
        'subscription_code',
        'price',
        'start_at',
        'end_at'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
