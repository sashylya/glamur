<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;

class ProfileController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    public function orders()
    {
        /** @var User $user */
        $user = Auth::user();
        $orders = $user->orders()->orderByDesc('created_at')->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('profile.order', compact('order'));
    }

    public function edit()
    {
        /** @var User $user */
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // Валидация только тех полей, которые есть в форме
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ];

        // Добавляем валидацию для пароля только если он передан
        if ($request->filled('current_password') || $request->filled('new_password')) {
            $rules['current_password'] = 'required_with:new_password|current_password';
            $rules['new_password'] = 'required|string|min:8|confirmed';
        }

        $data = $request->validate($rules);

        // Обновляем только имя и телефон
        $user->name = $data['name'];
        $user->phone = $data['phone'] ?? null;
        
        // Обновляем пароль если передан
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Профиль обновлен');
    }
}