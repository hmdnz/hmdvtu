<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'walletID',
        'adminID',
        'reference',
        'provider_reference',
        'gateway',
        'channel',
        'amount',
        'balanceBefore',
        'balanceAfter',
        'fees',
        'total',
        'status',
        'response',
        'canceled_by',
        'canceled_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userID','id');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }


    /**
     * Scope to find a payment by reference
     */
    public function scopeWithReference($query, $reference)
    {
        return $query->where('reference', $reference);
    }

}
