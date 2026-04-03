<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // показываем форму
    }

    public function login(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $validToken = 'E6kUTYrYwZq2tN4QEtyzsbEBk3ie'; // ваш токен из задания

        if ($request->input('token') === $validToken) {
            Session::put('authenticated', true);
            return redirect('/dashboard');
        } else {
            return back()->withErrors(['token' => 'Неверный токен']);
        }
    }

    public function logout()
    {
        Session::forget('authenticated');
        return redirect('/');
    }
}
