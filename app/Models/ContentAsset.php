<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentAsset extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content_id',
        'type', // video, audio, subtitle, thumbnail, document
        'url',
        'path',
        'mime_type',
        'size',
        'duration',
        'resolution',
        'bitrate',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'size' => 'integer',
        'duration' => 'integer',
        'bitrate' => 'integer',
    ];

    /**
     * Get the content that owns the asset.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Scope a query to only include assets of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include active assets.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
} 