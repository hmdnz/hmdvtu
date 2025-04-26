<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'billerID',
        'packageID',
        'service',
        'provider',
        'reference',
        'price',
        'quantity',
        'total',
        'beneficiary',
        'sender',
        'message',
        'meterType',
        'meterNumber',
        'meterName',
        'responseAPI',
        'responseMessage',
        'responseBody',
        'token',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function biller()
    {
        return $this->belongsTo(Biller::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'packageID');
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'orderID');
    }

    /**
     * Scope to find a order by reference
     */
    public function scopeWithReference($query, $reference)
    {
        return $query->where('reference', $reference);
    }

}
