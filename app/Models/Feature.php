<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status',
    ];

    public function votes(): HasMany
    {
        return $this->hasMany(FeatureVote::class);
    }
}