<?php

namespace App\Http\Middleware;

use Closure;

class FieldAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->session()->exists('field')) {
            return $next($request);
        }

        return redirect('/field/login');;
    }
}
