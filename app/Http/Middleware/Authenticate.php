<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        $guard =  Auth::getDefaultDriver();
        // Customize the redirection logic based on the guard
        switch ($guard) {
            case 'admin':
                return route('admin.login'); // Change 'admin.login' to your admin login route
                break;

            case 'web':
                return route('user.login'); // Change 'user.login' to your user login route
                break;

            default:
                return route('user.login'); // Fallback to a general login route
                break;
        }
    }
}
