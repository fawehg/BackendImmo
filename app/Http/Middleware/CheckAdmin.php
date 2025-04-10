<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        $admin = Admin::find(auth()->id());

        if (!$admin || !$admin->isAdmin()) {
            return redirect()->route('home')->with('error', 'Vous n\'avez pas les autorisations nécessaires.');
        }

        return $next($request);
    }
    
}
