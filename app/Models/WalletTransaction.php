<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $table = 'wallet_transactions';

    protected $fillable = [
        'wallet_owner_id',
        'payer_id',
        'transaction_id',
        'tx_ref',
        'amount',
        'currency',      // 👈 ADDED - for multi-currency support
        'status',
        'type',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who owns this wallet transaction (receives money)
     */
    public function walletOwner()
    {
        return $this->belongsTo(UserRecord::class, 'wallet_owner_id', 'id');
    }

    /**
     * Get the user who made the payment (sends money)
     */
    public function payer()
    {
        return $this->belongsTo(UserRecord::class, 'payer_id', 'id');
    }

    /**
     * Scope for successful transactions only
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'successful');
    }

    /**
     * Scope for specific currency
     */
    public function scopeCurrency($query, $currency)
    {
        return $query->where('currency', $currency);
    }

    /**
     * Scope for specific user (either as owner or payer)
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function($q) use ($userId) {
            $q->where('wallet_owner_id', $userId)
              ->orWhere('payer_id', $userId);
        });
    }

    /**
     * Get formatted amount with currency symbol
     */
    public function getFormattedAmountAttribute()
    {
        $symbols = [
            'NGN' => '₦',
            'USD' => '$',
            'GBP' => '£',
            'EUR' => '€',
            'GHS' => '₵',
            'KES' => 'KSh',
            'TZS' => 'TSh',
            'UGX' => 'USh',
            'ZAR' => 'R',
            'XAF' => 'FCFA',
            'XOF' => 'CFA'
        ];

        $symbol = $symbols[$this->currency] ?? $this->currency;
        // To this:
        return $symbol . ' ' . number_format((float) $this->amount, 2);
    }

    /**
     * Check if transaction is a credit (positive amount)
     */
    public function getIsCreditAttribute()
    {
        return $this->amount > 0;
    }

    /**
     * Check if transaction is a debit (negative amount)
     */
    public function getIsDebitAttribute()
    {
        return $this->amount < 0;
    }
}