<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CableCustomer extends Model
{
    use HasFactory;
    protected $table = "cable_customers";
    protected $fillable = [
        'userID',
        'name',
        'smartcard',
        'biller',
        'address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
