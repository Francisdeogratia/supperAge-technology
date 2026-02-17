<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = [
        'advertiser_id',
        'title',
        'description',
        'ad_type',
        'media_url',
        'media_type',
        'cta_text',
        'cta_link',
        'status',
        'target_countries',
        'target_age_range',
        'target_gender',
        'target_interests',
        'budget',
        'spent',
        'currency',
        'start_date',
        'end_date',
        'cost_per_click',
        'cost_per_impression',
        'impressions',
        'clicks',
        'conversions',
        'rejection_reason',
        'daily_budget',
       'payment_status',
       'payment_reference',
       'flutterwave_tx_ref',
       'paid_at',
       'cost_per_action',
       'cost_per_mille',
         'actions', // Add this
    ];

    protected $casts = [
        'target_countries'   => 'array',
        'target_age_range'   => 'array',
        'target_gender'      => 'array',
        'target_interests'   => 'array',

        'budget'             => 'float',
        'spent'              => 'float',
        'cost_per_click'     => 'float',
        'cost_per_impression'=> 'float',

        'impressions'        => 'integer',
        'clicks'             => 'integer',
        'conversions'        => 'integer',

        'start_date'         => 'date',
        'end_date'           => 'date',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(UserRecord::class, 'advertiser_id');
    }

    public function advertiser()
    {
        return $this->belongsTo(UserRecord::class, 'advertiser_id');
    }

    public function adImpressions()
    {
        return $this->hasMany(AdImpression::class, 'ad_id');
    }

    public function adClicks()
    {
        return $this->hasMany(AdClick::class, 'ad_id');
    }

    public function isActive()
    {
        return $this->status === 'active'
            && $this->start_date <= now()
            && $this->end_date >= now()
            && $this->spent < $this->budget;
    }
    /**
     * Get Click-Through Rate percentage
     */
    public function getCTR()
    {
        if ($this->impressions == 0) return 0;
        return round(($this->clicks / $this->impressions) * 100, 2);
    }

    /**
     * Get conversion rate percentage
     */
    public function getConversionRate()
    {
        if ($this->clicks == 0) return 0;
        return round(($this->conversions / $this->clicks) * 100, 2);
    }

    /**
     * Get remaining budget
     */
    public function getRemainingBudget()
    {
        return $this->budget - $this->spent;
    }

    /**
     * Get days remaining in campaign
     */
    public function getDaysRemaining()
    {
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Calculate budget remaining (accessor)
     */
    public function getBudgetRemainingAttribute()
    {
        return max(0, $this->budget - $this->spent);
    }

    /**
     * Calculate estimated reach based on impressions
     */
    public function getEstimatedReachAttribute()
    {
        if ($this->cost_per_impression == 0) {
            return 0;
        }
        return (int) ($this->budget / $this->cost_per_impression);
    }

    /**
     * Scope: Get only active ads
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now())
                    ->whereColumn('spent', '<', 'budget');
    }

    /**
     * Scope: Get ads pending approval
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get rejected ads
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function adActions()
{
    return $this->hasMany(AdAction::class, 'ad_id');
}

public function getConversionRatecpa()
{
    if ($this->clicks == 0) return 0;
    return round(($this->actions / $this->clicks) * 100, 2);
}
}