<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected function getCart()
    {
        if (Auth::check()) {
            $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        } else {
            $sessionId = session()->getId();
            $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        }
        
        return $cart->load('items.product');
    }

    public function index()
    {
        $cart = $this->getCart();
        return view('cart.index', compact('cart'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        if ($product->stock <= 0) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Товара нет в наличии'], 422);
            }
            return back()->with('error', 'Товара нет в наличии');
        }

        $cart = $this->getCart();
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        $currentQuantity = $cartItem ? $cartItem->quantity : 0;
        $newQuantity = $currentQuantity + $request->quantity;

        if ($newQuantity > $product->stock) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Недостаточно товара на складе'], 422);
            }
            return back()->with('error', 'Недостаточно товара на складе');
        }
        
        if ($cartItem) {
            $cartItem->increment('quantity', $request->quantity);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            $cart = $this->getCart();
            return response()->json([
                'success' => true,
                'message' => 'Товар добавлен в корзину',
                'cartCount' => $cart->items->sum('quantity'),
                'cartTotal' => $cart->items->sum(function($item) {
                    return $item->product->price * $item->quantity;
                })
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);

        if ($request->quantity > $item->product->stock) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Недостаточно товара на складе'], 422);
            }
            return back()->with('error', 'Недостаточно товара на складе');
        }
        
        if ($request->quantity <= 0) {
            $item->delete();
        } else {
            $item->update(['quantity' => $request->quantity]);
        }

        // Пересчитываем корзину
        $cart = $this->getCart();
        $cartTotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        $cartCount = $cart->items->sum('quantity');

        // Если запрос AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Количество обновлено',
                'itemTotal' => $item->exists ? ($item->product->price * $item->quantity) : 0,
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Корзина обновлена');
    }

    public function destroy(Request $request, $itemId)
    {
        $cart = $this->getCart();
        $item = $cart->items()->findOrFail($itemId);
        $item->delete();

        // Пересчитываем корзину после удаления
        $cart = $this->getCart();
        $cartTotal = $cart->items->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        $cartCount = $cart->items->sum('quantity');

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Товар удален из корзины',
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'isEmpty' => $cartCount === 0
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Товар удален из корзины');
    }
}