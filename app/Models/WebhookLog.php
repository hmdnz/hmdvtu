<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    use HasFactory;
    protected $table = 'webhook_logs';

    protected $fillable =[
        'provider',
        'service',
        'biller',
        'reference',
        'response',
        'status',
    ];
}
