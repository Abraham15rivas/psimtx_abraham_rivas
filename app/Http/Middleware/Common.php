<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Common
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = \Auth::user();
        if ($user->role_id == 2) {
            return $next($request);
        } else {
            return redirect()->to('home');
        }
    }
}
