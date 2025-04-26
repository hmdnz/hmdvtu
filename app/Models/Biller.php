<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biller extends Model
{
    use HasFactory;
    protected $fillable = [
        'adminID',
        'title',
        'service',
        'status',
        'variation',
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
