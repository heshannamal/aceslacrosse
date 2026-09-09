<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }
        $user = auth()->user();
        if (!method_exists($user, 'canAccessAdmin') || !$user->canAccessAdmin()) {
            abort(403, 'You do not have access to the admin panel.');
        }
        return $next($request);
    }
}
