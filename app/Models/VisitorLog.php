<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'flow_id',
        'ip',
        'country',
        'device',
        'browser',
        'referer',
        'user_agent',
        'isp',
        'reason',
    ];
    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }
}
