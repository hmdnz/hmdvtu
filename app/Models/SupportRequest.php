<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
    use HasFactory;
    protected $table = "support_requests";
    protected $fillable = [
        'userID',
        'reference',
        'phone',
        'type',
        'category',
        'service',
        'body',
        'feedback',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
