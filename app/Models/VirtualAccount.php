<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VirtualAccount extends Model
{
    use HasFactory;

    protected $table = 'virtual_accounts';
    
    protected $fillable = [
        'userID',
        'walletID',
        'accountName',
        'accountNumber',
        'accountBank',
        'provider',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'walletID');
    }

}
