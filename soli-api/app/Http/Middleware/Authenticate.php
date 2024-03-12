<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $response = $next($request);
            return $response;
        }
//        Unauthorized
        return response()->json(reshelper()->withFormat(null, 'Unauthorized', 'error', false, true));
    }
}
