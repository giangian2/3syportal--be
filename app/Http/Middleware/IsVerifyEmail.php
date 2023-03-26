<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->email_verified_at) {
            
            $response = [
                'message' => 'you need to verify your account',
            ];
            return response()->json($response, 413);
          }
   
        return $next($request);
    }
}
