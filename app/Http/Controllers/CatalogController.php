<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images')
            ->where('is_active', true);

        // Фильтр по категории
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Фильтр по цене
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }

        // Фильтр по материалу
        if ($request->has('material') && $request->material != '') {
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
        $materials = Product::distinct()->pluck('material');

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

    public function category(Category $category)
    {
        $products = $category->products()
            ->with('images')
            ->where('is_active', true)
            ->paginate(12);

        return view('catalog.category', compact('category', 'products'));
    }
}