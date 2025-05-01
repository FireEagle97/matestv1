<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'producer_id',
        'title',
        'description',
        'genre',
        'price',
        'status',
        'is_featured',
        'thumbnail_url'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
    ];

    public function producer()
    {
        return $this->belongsTo(Producer::class);
    }

    public function assets()
    {
        return $this->hasMany(MovieAsset::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
} 