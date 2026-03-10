<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Список категорий
     */
    public function index()
    {
        $categories = Category::withCount('products')->orderBy('sort')->get();
        return view('admin.categories.index', compact('categories'));
    }
    
    /**
     * Форма создания категории
     */
    public function create()
    {
        return view('admin.categories.form');
    }
    
    /**
     * Сохранение новой категории
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories',
            'description' => 'nullable|string',
            'sort' => 'nullable|integer',
        ]);
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        if (empty($data['sort'])) {
            $data['sort'] = Category::max('sort') + 1;
        }
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/categories', 'public');
            $data['image'] = 'storage/' . $path;
        }
        
        Category::create($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория создана');
    }
    
    /**
     * Форма редактирования категории
     */
    public function edit(Category $category)
    {
        return view('admin.categories.form', compact('category'));
    }
    
    /**
     * Обновление категории
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'sort' => 'nullable|integer',
        ]);
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Обработка изображения
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/categories', 'public');
            $data['image'] = 'storage/' . $path;
        }
        
        $category->update($data);
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория обновлена');
    }
    
    /**
     * Удаление категории
     */
    public function destroy(Category $category)
    {
        // Проверяем, есть ли товары в категории
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Нельзя удалить категорию, в которой есть товары');
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('success', 'Категория удалена');
    }
}