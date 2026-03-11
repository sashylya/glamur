<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller; // ← ИЗМЕНЕНО: убрал "as BaseController"

class ReviewController extends Controller // ← ИЗМЕНЕНО: теперь просто Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin'); // если нужна проверка админа
    }

    public function index()
{
    $reviews = Review::with(['user', 'product'])
        ->where('is_approved', false) // Только неодобренные
        ->orderByDesc('created_at')
        ->paginate(20);

    return view('admin.reviews.index', compact('reviews'));
}

    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);
        return back()->with('success', 'Отзыв одобрен');
    }

    public function reject(Review $review)
    {
        $review->delete();
        return back()->with('success', 'Отзыв отклонен и удален');
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

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'advantages' => $request->advantages,
            'disadvantages' => $request->disadvantages,
            'is_approved' => false, // Отзыв отправляется на модерацию
        ]);

        return back()->with('success', 'Спасибо за отзыв! Он появится после проверки модератором.');
    }

    public function update(Request $request, Review $review)
    {
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
        if (Auth::id() !== $review->user_id && !Auth::user()?->is_admin) {
            abort(403);
        }
        
        $review->delete();

        return back()->with('success', 'Отзыв удален');
    }
}