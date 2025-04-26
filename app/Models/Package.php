<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'adminID',
        'billerID',
        'title',
        'service',
        'provider',
        'type',
        'size',
        'cost',
        'price',
        'validity',
        'planType',
        'planID',
        'status',
    ];

    public function biller()
    {
        return $this->belongsTo(Biller::class, 'billerID','id');
    }
    public function providersingle()
    {
        return $this->belongsTo(Provider::class, 'provider','id');
    }

    
}
