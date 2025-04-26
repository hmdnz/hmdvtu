<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnergyCustomer extends Model
{
    use HasFactory;
    
    protected $table = "energy_customers";
    protected $fillable = [
        'userID',
        'name',
        'meterNumber',
        'meterType',
        'disco',
        'address',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
