<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
   

            protected  $fillable = [
                'name',
                'description',
                'feature_acs_cloaking_script',
                'feature_api_geolocation',
                'feature_api_blocker',
                'domain_quota',
                'visitor_quota_perday',
                'active',
                'price',
                'billing_cycle'
            ];
}

    
   