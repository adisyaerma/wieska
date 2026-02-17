<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Kirim data role dan pesan sukses ke session
            return redirect()->back()->with([
                'loginSuccess' => true,
                'jabatan' => $user->jabatan,
            ]);
        }

        return back()->with('loginError', 'Email atau password salah!');
    }



    public function dashboardAdmin()
    {
        return view('admin.dashboard');
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
