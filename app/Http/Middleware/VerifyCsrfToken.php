<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
        // 'http://127.0.0.1:8000/webhook/rva-transfer?token=zaumadata4f0f3612345327f3eb977b33ujhgtaab60oiu'
        'https://zaumadata.com.ng/webhook/rva-transfer?token=zaumadata4f0f3612345327f3eb977b33ujhgtaab60oiu'
    ];
}
