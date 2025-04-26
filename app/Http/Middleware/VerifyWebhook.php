<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Retrieve the secret key from the request header
        $incomingSecret = $request->query('token');

        // Compare with the expected secret key stored in configuration
        $expectedSecret = config('webhook.secret');;

        if ($incomingSecret !== $expectedSecret) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
