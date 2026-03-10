<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class WishlistController extends BaseController
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Показать список избранного
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $wishlist = $user->wishlists()->with('product.images')->get();
        
        return view('wishlist.index', compact('wishlist'));
    }

    /**
     * Добавить товар в избранное
     */
    public function store(Product $product)
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user->wishlists()->where('product_id', $product->id)->exists()) {
            $user->wishlists()->create(['product_id' => $product->id]);
            return back()->with('success', 'Товар добавлен в избранное');
        }

        return back()->with('info', 'Товар уже в избранном');
    }

    /**
     * Удалить товар из избранного
     */
    public function destroy(Product $product)
    {
        /** @var User $user */
        $user = Auth::user();
        $user->wishlists()->where('product_id', $product->id)->delete();
        
        return back()->with('success', 'Товар удален из избранного');
    }
}