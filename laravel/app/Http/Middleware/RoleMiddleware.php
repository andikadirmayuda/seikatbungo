<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Middleware untuk memeriksa role user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user) {
            abort(403, 'Akses tidak diizinkan. User tidak ditemukan.');
        }

        // Pastikan method hasRole ada pada user
        if (!method_exists($user, 'hasRole')) {
            abort(500, 'Method hasRole tidak ditemukan pada model User.');
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Akses tidak diizinkan. Anda tidak memiliki role yang sesuai.');
    }
}
