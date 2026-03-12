<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; // ← Добавьте этот импорт

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // Если выбрано "Все", сбрасываем фильтры и перенаправляем на чистый каталог
        if ($request->get('sort') === 'all') {
            return redirect()->route('catalog.index');
        }

        $query = Product::with('category', 'images')
            ->where('is_active', true);

        // УЛУЧШЕННЫЙ ПОИСК (умный поиск по словам)
        if ($request->filled('search')) {
            $search = $request->search;
            // Разбиваем запрос на отдельные слова (минимум 2 символа)
            $words = collect(explode(' ', $search))->filter(fn($w) => mb_strlen($w) >= 2);

            if ($words->isNotEmpty()) {
                $query->where(function($q) use ($words) {
                    foreach ($words as $word) {
                        // Каждое слово должно встречаться хотя бы в одном из полей
                        $q->where(function($sub) use ($word) {
                            $sub->where('name', 'like', "%{$word}%")
                                ->orWhere('sku', 'like', "%{$word}%")
                                ->orWhere('description', 'like', "%{$word}%");
                        });
                    }
                });
            } else {
                // Если слова слишком короткие, ищем как раньше по всей строке
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            }
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $categoryVal = $request->category;
            if (is_array($categoryVal)) {
                $query->whereIn('category_id', $categoryVal);
            } elseif (str_contains($categoryVal, ',')) {
                $query->whereIn('category_id', explode(',', $categoryVal));
            } else {
                $query->where('category_id', $categoryVal);
            }
        }

        // Фильтр "Новинка"
        if ($request->has('is_new')) {
            $query->where('is_new', true);
        }

        // Фильтр по цене
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Фильтр по материалу
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        // Сортировка
        $sort = $request->get('sort', 'popularity');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('popularity');
        }

        $products = $query->paginate(12)->withQueryString();
        
        $categories = Category::withCount('products')->get();
        $materials = Product::distinct()->pluck('material')->filter();

        // Формируем заголовок каталога
        $title = 'Каталог украшений';
        if ($request->filled('category')) {
            $categoryIds = is_array($request->category) ? $request->category : (str_contains($request->category, ',') ? explode(',', $request->category) : [$request->category]);
            $selectedCategoryNames = Category::whereIn('id', $categoryIds)->pluck('name')->toArray();
            if (!empty($selectedCategoryNames)) {
                $title = implode(', ', $selectedCategoryNames);
            }
        }
        if ($request->has('is_new')) {
            $title .= ' (Новинки)';
        }

        return view('catalog.index', compact('products', 'categories', 'materials', 'title'));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $related = Product::with('images')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('catalog.show', compact('product', 'related'));
    }

    public function category(Request $request, Category $category)
    {
        // ИСПРАВЛЕНО: используем Product::where вместо $category->products()
        $query = Product::with('images')
            ->where('category_id', $category->id)
            ->where('is_active', true);

        // ПОИСК в категории (умный поиск по словам)
        if ($request->filled('search')) {
            $search = $request->search;
            $words = collect(explode(' ', $search))->filter(fn($w) => mb_strlen($w) >= 2);

            if ($words->isNotEmpty()) {
                $query->where(function($q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function($sub) use ($word) {
                            $sub->where('name', 'like', "%{$word}%")
                                ->orWhere('sku', 'like', "%{$word}%");
                        });
                    }
                });
            } else {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            }
        }

        // Фильтр по цене
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Фильтр по материалу
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        // Сортировка
        $sort = $request->get('sort', 'popularity');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderByDesc('created_at');
                break;
            default:
                $query->orderByDesc('popularity');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('catalog.category', compact('category', 'products'));
    }
}