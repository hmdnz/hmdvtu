<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLogs extends Model
{
    use HasFactory;
    protected $table = 'admin_logs';
    
    protected $fillable = [
        'adminID',
        'username',
        'IPAddress',
        'status',
    ];
}
