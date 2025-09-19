<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documentation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'order',
        'category',
        'version',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Scope untuk mendapatkan dokumen yang dipublish
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Scope untuk mengurutkan berdasarkan order
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Mendapatkan next page
    public function next()
    {
        return static::where('order', '>', $this->order)
            ->published()
            ->ordered()
            ->first();
    }

    // Mendapatkan previous page
    public function previous()
    {
        return static::where('order', '<', $this->order)
            ->published()
            ->ordered()
            ->latest('order')
            ->first();
    }
}