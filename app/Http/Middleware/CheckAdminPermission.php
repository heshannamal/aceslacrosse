<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Auth;
class CheckAdminPermission
{
    public function handle($request, Closure $next, $permission)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('admin.login');
        if (!method_exists($user, 'hasAdminPermission') || !$user->hasAdminPermission($permission)) {
            return redirect()->route('admin.dashboard')->with('error', 'You do not have permission to access this page.');
        }
        return $next($request);
    }
}
