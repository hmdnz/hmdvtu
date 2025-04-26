<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;
    protected $fillable = [
        'adminID',
        'title',
        'body',
        'status',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

}
