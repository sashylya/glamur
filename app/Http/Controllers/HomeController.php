<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // КАТЕГОРИИ для блока сверху (все 4 категории)
        $categories = Category::orderBy('sort')->get();

        // БЛОК 1: Кольца (новинки) - category_id = 2
        $collectionProducts = Product::with('images')
            ->where('is_active', true)
            ->where('category_id', 2) // Кольца
            ->where('is_new', true)
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Если нет новинок в кольцах, берем популярные кольца
        if ($collectionProducts->isEmpty()) {
            $collectionProducts = Product::with('images')
                ->where('is_active', true)
                ->where('category_id', 2) // Кольца
                ->orderByDesc('created_at')
                ->take(3)
                ->get();
        }

        // БЛОК 2: Серьги (id=3) и Подвески (id=5)
        $gridProducts = Product::with('images')
            ->where('is_active', true)
            ->whereIn('category_id', [3, 5]) // Серьги и Подвески
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Если нет товаров в серьгах/подвесках, берем любые популярные
        if ($gridProducts->isEmpty()) {
            $gridProducts = Product::with('images')
                ->where('is_active', true)
                ->orderByDesc('created_at')
                ->take(4)
                ->get();
        }

        // БЛОК 3: Браслеты (популярные) - category_id = 4
        $anotherCollectionProducts = Product::with('images')
            ->where('is_active', true)
            ->where('category_id', 4) // Браслеты
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // Если нет браслетов, берем популярные товары из других категорий
        if ($anotherCollectionProducts->isEmpty()) {
            $anotherCollectionProducts = Product::with('images')
                ->where('is_active', true)
                ->orderByDesc('created_at')
                ->take(3)
                ->get();
        }

        return view('home', compact(
            'categories',
            'collectionProducts',
            'gridProducts',
            'anotherCollectionProducts'
        ));
    }
}