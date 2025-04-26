<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'identifier',
        'mainBalance',
        'referralBalance',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function virtualAccounts()
    {
        return $this->hasMany(VirtualAccount::class)
        ->whereNotNull('status');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)
        ->whereNotNull('status');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class)
        ->whereNotNull('status');
    }
}
