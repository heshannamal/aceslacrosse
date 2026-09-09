<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function login(){ if(Auth::check() && method_exists(Auth::user(),'canAccessAdmin') && Auth::user()->canAccessAdmin()) return redirect()->route('admin.dashboard'); return view('admin.login'); }
    public function authenticate(Request $request)
    {
        $credentials=$request->validate(['email'=>['required','email'],'password'=>['required']]);
        if(Auth::attempt($credentials,$request->boolean('remember'))){
            $request->session()->regenerate();
            if(!Auth::user()->canAccessAdmin()){ Auth::logout(); return back()->withErrors(['email'=>'This account does not have admin access.'])->onlyInput('email'); }
            return redirect()->intended(route('admin.dashboard'));
        }
        return back()->withErrors(['email'=>'Invalid login details.'])->onlyInput('email');
    }
    public function logout(Request $request){ Auth::logout(); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('admin.login'); }
}
