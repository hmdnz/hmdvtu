<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $table = "categories";
    protected $fillable = [
        'adminID',
        'service',
        'title',
        'mtn',
        'airtel',
        'mobile',
        'glo',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
