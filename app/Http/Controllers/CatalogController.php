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
        $query = Product::with('category', 'images')
            ->where('is_active', true);

        // ПОИСК по названию и артикулу
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Фильтр по категории
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
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

        return view('catalog.index', compact('products', 'categories', 'materials'));
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

        // ПОИСК в категории
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
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