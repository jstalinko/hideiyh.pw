<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    protected $fillable = [
        'package_id',
        'user_id',
        'invoice',
        'price',
        'fee',
        'payment_method',
        'status',
        'customer_email',
        'customer_phone',
        'customer_name'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
