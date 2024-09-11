<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            list($type, $credentials) = explode(' ', $authHeader, 2);

            if (strtolower($type) === 'basic') {
                $decodedCredentials = base64_decode($credentials, true);
                list($username, $password) = explode(':', $decodedCredentials, 2);

                // Hardcoded credentials
                $validUsername = env('API_USERNAME');
                $validPassword = env('API_PASSWORD');

                if ($username === $validUsername && $password === $validPassword) {
                    return $next($request);
                }
            }
        }

        throw new AuthenticationException();
    }
}
