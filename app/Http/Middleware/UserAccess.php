<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserType;

class UserAccess
{
    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    */
   public function handle(Request $request, Closure $next, $userTypes)
   {
        $user_type=UserType::fromValue(auth()->user()->type);
        $converted=UserType::toString($user_type);
        if (in_array($converted, explode('|', $userTypes))) {
            return $next($request);
        }

        /*
            if(auth()->user()->type == $userType){
                return $next($request);
            }
        */

       return response()->json(['You do not have permission to access for this page.'], 401);
       /* return response()->view('errors.check-permission'); */
   }
}
