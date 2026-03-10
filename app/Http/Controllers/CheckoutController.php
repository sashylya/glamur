<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User; // Добавить эту строку
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash; // Добавить эту строку

class CheckoutController extends Controller
{
    protected function getCart()
    {
        if (Auth::check()) {
            return Cart::with('items.product')->where('user_id', Auth::id())->first();
        } else {
            return Cart::with('items.product')->where('session_id', session()->getId())->first();
        }
    }

    public function index()
    {
        $cart = $this->getCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $user = Auth::user();
        
        return view('checkout.index', compact('cart', 'user'));
    }

    public function store(Request $request)
    {
        $cart = $this->getCart();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'notes' => 'nullable|string',
        ];

        // Если пользователь не авторизован, проверяем создание аккаунта
        if (!Auth::check()) {
            $rules['create_account'] = 'nullable|boolean';
            $rules['password'] = 'required_if:create_account,1|string|min:8|confirmed';
        }

        $data = $request->validate($rules);

        DB::beginTransaction();

        try {
            // Создаем заказ
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => Order::STATUS_NEW,
                'total_amount' => $cart->total,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'city' => $data['city'],
                'notes' => $data['notes'],
            ]);

            // Добавляем товары в заказ
            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);

                // Уменьшаем остаток на складе
                $item->product->decrement('stock', $item->quantity);
            }

            // Очищаем корзину
            $cart->items()->delete();
            $cart->delete();

            // Если пользователь хочет создать аккаунт
            if (!$request->user && $request->boolean('create_account')) {
                $user = User::create([
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'phone' => $data['phone'],
                ]);
                
                Auth::login($user);
            }

            DB::commit();

            return redirect()->route('checkout.success', $order);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

   public function success(Order $order)
{
    if ($order->user_id !== Auth::id() && (!Auth::user() || !Auth::user()->is_admin)) {
        abort(403);
    }

    return view('checkout.success', compact('order'));
}
}