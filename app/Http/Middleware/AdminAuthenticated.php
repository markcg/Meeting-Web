<?php

namespace App\Http\Middleware;

use Closure;

class AdminAuthenticated
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
        if ($request->session()->exists('admin')) {
            return $next($request);
        }

        return redirect('/admin/login');;
    }
}
