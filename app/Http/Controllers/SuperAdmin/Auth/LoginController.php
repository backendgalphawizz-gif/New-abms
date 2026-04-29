<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:super_admin', ['except' => ['logout']]);
    }

    public function login()
    {
        return view('super-admin.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::guard('super_admin')->attempt(
            $request->only('email', 'password'),
            $request->boolean('remember')
        )) {
            return redirect()->intended(route('super-admin.dashboard'));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => __('These credentials do not match our records.')]);
    }

    public function logout(Request $request)
    {
        Auth::guard('super_admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('super-admin.auth.login');
    }
}
