<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Список товаров
     */
    public function index(Request $request)
    {
        $query = Product::with('category', 'images');
        
        // Поиск
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', "%{$request->search}%");
        }
        
        // Фильтр по категории
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Фильтр по наличию
        if ($request->has('stock') && $request->stock != '') {
            switch ($request->stock) {
                case 'in':
                    $query->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', '<=', 0);
                    break;
                case 'low':
                    $query->where('stock', '<', 5)->where('stock', '>', 0);
                    break;
            }
        }
        
        $products = $query->orderByDesc('id')->paginate(15);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }
    
    /**
     * Форма создания товара
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.form', compact('categories'));
    }
    
    /**
     * Сохранение нового товара
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products',
            'description' => 'required|string',
            'material' => 'required|string',
            'hallmark' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'stone' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'popularity' => 'nullable|integer',
            'is_new' => 'nullable|boolean',
        ]);
        
        // Генерация slug, если не указан
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $data['is_new'] = $request->has('is_new');
        
        $product = Product::create($data);
        
        // Обработка изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                // Сохраняем в storage/app/public/images/products
                $path = $file->store('images/products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => 'storage/' . $path, // Путь для отображения через symlink
                    'sort' => $index,
                    'is_main' => $index === 0,
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно создан');
    }
    
    /**
     * Форма редактирования товара
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $product->load('images');
        return view('admin.products.form', compact('product', 'categories'));
    }
    
    /**
     * Обновление товара
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:products,slug,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'description' => 'required|string',
            'material' => 'required|string',
            'hallmark' => 'nullable|string',
            'weight' => 'nullable|numeric',
            'stone' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'popularity' => 'nullable|integer',
            'is_new' => 'nullable|boolean',
        ]);
        
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        $data['is_new'] = $request->has('is_new');
        
        $product->update($data);
        
        // Обработка новых изображений
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('images/products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => 'storage/' . $path,
                    'sort' => $product->images->count() + $index,
                    'is_main' => false,
                ]);
            }
        }
        
        // Обновление главного изображения
        if ($request->has('main_image')) {
            ProductImage::where('product_id', $product->id)->update(['is_main' => false]);
            ProductImage::where('id', $request->main_image)->update(['is_main' => true]);
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар успешно обновлен');
    }
    
    /**
     * Удаление товара
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', 'Товар удален');
    }
}