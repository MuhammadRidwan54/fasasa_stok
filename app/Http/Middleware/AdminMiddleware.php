<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek jika user sudah login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        // Cek jika user adalah admin
        if (Auth::user()->isAdmin()) {
            return $next($request);
        }

        // Jika bukan admin, redirect ke login dengan error
        Auth::logout();
        return redirect()->route('login')->with('error', 'Anda tidak memiliki akses admin.');
    }
}