<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->email == env('ADMIN_EMAIL')) {

            if (Hash::check($request->password, env('ADMIN_PASSWORD'))) {
                return $next($request); // السماح بالمتابعة
            }
    
            return response()->json(['message' => 'Unauthorized'], 401); // إذا كانت كلمة المرور غير صحيحة
        }
            return response()->json(['message' => 'Unauthorized'], 401);
    }
  
}
