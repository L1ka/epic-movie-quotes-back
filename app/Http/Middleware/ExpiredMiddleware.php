<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Config;

class ExpiredMiddleware
{
	/*
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */

	// public function handle($request, Closure $next, $expirationConfig)
	// {
	//     $expiration = Config::get($expirationConfig, 1); // Default expiration if config value is not found
	//     $routeExpiration = now()->subMinutes($expiration);

	//     if ($request->route('expires_at')->lt($routeExpiration)) {
	//         // return redirect()->route('/')->withErrors([
	//         //     'verification' => 'The verification link has expired.',
	//         // ]);
	//         return response()->json([ 'error' => 'expired' ]);
	//     }

	//     return $next($request);
	// }
}
