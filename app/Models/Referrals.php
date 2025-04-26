<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referrals extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'referrer',
        'commission',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'userID');
    }

    public function referrerUser()
    {
        return $this->belongsTo(User::class,'referrer');
    }
}
