<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Switches extends Model
{
    use HasFactory;

    protected $table = "switches";

    protected $fillable = [
        'context_type',
        'context_id',
        'category_title',
        'provider_id',
        'service_id',
        'status',
        'updated_by',
    ];

    // public function provider()
    // {
    //     return $this->hasOne(Provider::class,'id', 'providerID');
    // }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    
    public function biller()
    {
        return $this->belongsTo(Biller::class, 'context_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

}
