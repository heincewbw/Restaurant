<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
        }

        if (!$user->is_admin) {
            return back()->withErrors(['email' => 'Akses admin diperlukan'])->withInput();
        }

        session(['user_id' => $user->id]);

        return redirect()->route('admin.menu.index');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showSimpleLogin()
    {
        return view('auth.login-simple');
    }

    public function simpleLogin(Request $request)
    {
        $data = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $users = [
            'admin' => [
                'password' => '123',
                'role' => 'admin',
            ],
            'koki' => [
                'password' => '234',
                'role' => 'koki',
            ],
        ];

        $username = strtolower($data['username']);
        $password = $data['password'];

        if (!isset($users[$username]) || $users[$username]['password'] !== $password) {
            return back()->withInput()->with('error', 'Username atau password salah');
        }

        session(['simple_user' => [
            'username' => $username,
            'role' => $users[$username]['role'],
        ]]);

        // Redirect sesuai role
        if ($users[$username]['role'] === 'admin') {
            return redirect()->route('admin.menu.index');
        } else {
            return redirect()->route('kitchen');
        }
    }

    public function simpleLogout(Request $request)
    {
        $request->session()->forget('simple_user');
        return redirect()->route('login.simple');
    }
}
