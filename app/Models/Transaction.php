<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'walletID',
        'orderID',
        'reference',
        'provider_reference',
        'type',
        'category',
        'balanceBefore',
        'amount',
        'balanceAfter',
        'note',
        'status',
        'response',
        'canceled_by',
        'canceled_reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID');
    }
}
