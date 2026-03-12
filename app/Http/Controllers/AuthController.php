<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Редирект на запрошенную страницу или на главную
            $redirect = $request->input('redirect', route('home'));
            return redirect($redirect)->with('success', 'Вы успешно вошли');
        }

        return back()->withErrors([
            'email' => 'Неверный email или пароль',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => ['required', 'string', 'regex:/^(\+7|8|7)[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/'],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Укажите ваше имя',
            'email.required' => 'Email обязателен для регистрации',
            'email.email' => 'Введите корректный адрес почты',
            'email.unique' => 'Этот email уже используется',
            'phone.required' => 'Номер телефона необходим для регистрации',
            'phone.regex' => 'Введите корректный номер телефона (например, +7 900 000 00 00)',
            'password.required' => 'Придумайте пароль',
            'password.min' => 'Пароль должен быть не менее 8 символов',
            'password.confirmed' => 'Пароли не совпадают',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        // Редирект на запрошенную страницу или на главную
        $redirect = $request->input('redirect', route('home'));
        return redirect($redirect)->with('success', 'Регистрация успешна');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}