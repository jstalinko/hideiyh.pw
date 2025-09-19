<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainQuota extends Model
{
    use HasFactory;
    

     protected $fillable = [
        'user_id',
        'current_domain_quota',
        'amount',
        'total_price',
        'status'
     ];
}
