<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestCategory extends Model
{
    use HasFactory;
    protected $table = "request_categories";
    protected $fillable = [
        'adminID',
        'title',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
