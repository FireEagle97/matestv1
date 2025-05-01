<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Content extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'producer_id',
        'title',
        'description',
        'type',
        'status',
        'views_count',
        'downloads_count',
        'price',
        'featured',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'featured' => 'boolean',
        'price' => 'decimal:2',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
    ];

    /**
     * Get the producer that owns the content.
     */
    public function producer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'producer_id');
    }

    /**
     * Get the assets for the content.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(ContentAsset::class);
    }

    /**
     * Get the purchases for the content.
     */
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Scope a query to only include active content.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include featured content.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
} 