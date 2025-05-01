<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'content_id',
        'price_paid',
        'commission_amount',
        'commission_pct',
        'payment_method',
        'payment_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price_paid' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'commission_pct' => 'decimal:2',
    ];

    /**
     * Get the user that made the purchase.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the content that was purchased.
     */
    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }

    /**
     * Scope a query to only include successful purchases.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include purchases for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include purchases of a specific content.
     */
    public function scopeForContent($query, int $contentId)
    {
        return $query->where('content_id', $contentId);
    }
} 