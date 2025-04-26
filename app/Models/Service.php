<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'adminID',
        'providerID',
        'title',
        'status',
    ];

    public function provider()
    {
        return $this->hasOne(Provider::class,'id', 'providerID');
    }
}
