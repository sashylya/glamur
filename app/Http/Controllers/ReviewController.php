<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController; 

class ReviewController extends BaseController 
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index(Product $product)
    {
        $reviews = $product->approvedReviews()
            ->with('user')
            ->orderByDesc('created_at')
            ->paginate(10);

        return response()->json($reviews);
    }

    public function store(Request $request, Product $product)
{
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:2000',
        'advantages' => 'nullable|string|max:500',
        'disadvantages' => 'nullable|string|max:500',
    ]);

    // Проверяем, не оставлял ли пользователь уже отзыв
    $existingReview = Review::where('user_id', Auth::id())
        ->where('product_id', $product->id)
        ->first();

    if ($existingReview) {
        return back()->with('error', 'Вы уже оставляли отзыв на этот товар');
    }

    // СОЗДАЕМ ОТЗЫВ
    Review::create([
        'user_id' => Auth::id(),
        'product_id' => $product->id,
        'rating' => $request->rating,
        'comment' => $request->comment,
        'advantages' => $request->advantages,
        'disadvantages' => $request->disadvantages,
        'is_approved' => false,
    ]);

    return back()->with('success', 'Спасибо за отзыв! Он появится после проверки модератором.');
}

    public function update(Request $request, Review $review)
    {
        // Проверка авторизации
        if (Auth::id() !== $review->user_id && !Auth::user()?->is_admin) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:2000',
            'advantages' => 'nullable|string|max:500',
            'disadvantages' => 'nullable|string|max:500',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'advantages' => $request->advantages,
            'disadvantages' => $request->disadvantages,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Отзыв обновлен');
    }

    public function destroy(Review $review)
    {
        // Проверка авторизации
        if (Auth::id() !== $review->user_id && !Auth::user()?->is_admin) {
            abort(403);
        }
        
        $review->delete();

        return back()->with('success', 'Отзыв удален');
    }
}