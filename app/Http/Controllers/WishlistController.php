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

    public function index()
    {
        /** @var User $user */
        $user = Auth::user();
        $wishlist = $user->wishlists()->with('product.images')->get();
        
        return view('wishlist.index', compact('wishlist'));
    }

    public function store(Request $request, Product $product)
{
    /** @var User $user */
    $user = Auth::user();
    
    $exists = $user->wishlists()->where('product_id', $product->id)->exists();
    
    if ($exists) {
        $user->wishlists()->where('product_id', $product->id)->delete();
        $inWishlist = false;
        $message = 'Товар удален из избранного';
    } else {
        $user->wishlists()->create(['product_id' => $product->id]);
        $inWishlist = true;
        $message = 'Товар добавлен в избранное';
    }

    // ВАЖНО: проверяем, что это AJAX запрос
    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'inWishlist' => $inWishlist,
            'message' => $message
        ]);
    }

    return back()->with('success', $message);
}

    public function destroy(Request $request, Product $product)
    {
        /** @var User $user */
        $user = Auth::user();
        $user->wishlists()->where('product_id', $product->id)->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'inWishlist' => false,
                'message' => 'Товар удален из избранного'
            ]);
        }
        
        return back()->with('success', 'Товар удален из избранного');
    }
}