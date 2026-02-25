<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use HasFactory;

    protected $fillable = [
        'uniqid',
        'name',
        'render_white_page',
        'white_page_url',
        'render_bot_page',
        'bot_page_url',
        'render_offer_page',
        'offer_page_url',
        'allowed_countries',
        'block_vpn',
        'block_no_referer',
        'allowed_params',
        'acs',
        'blocker_bots',
        'lock_isp',
        'lock_referers',
        'lock_device',
        'lock_browser',
        'active',
    ];
    protected $casts = [
        'allowed_countries' => 'array',
        'lock_device' => 'array',
        'lock_browser' => 'array',
    ];
    public function visitorLogs()
    {
        return $this->hasMany(VisitorLog::class);
    }
}
