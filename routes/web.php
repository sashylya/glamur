<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController; // Только ОДИН импорт для обычного контроллера

// Импорты для админ-панели
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
// use App\Http\Controllers\Admin\ReviewController; // НЕ ИМПОРТИРУЙТЕ ЭТОТ!

// Главная
Route::get('/', [HomeController::class, 'index'])->name('home');

// Каталог
Route::prefix('catalog')->name('catalog.')->group(function () {
    Route::get('/', [CatalogController::class, 'index'])->name('index');
    Route::get('/category/{category}', [CatalogController::class, 'category'])->name('category');
    Route::get('/{product:slug}', [CatalogController::class, 'show'])->name('show'); 
});

// Корзина
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [CartController::class, 'store'])->name('add');
    Route::patch('/item/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/item/{item}', [CartController::class, 'destroy'])->name('remove');
});

// Оформление заказа
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});

// Статические страницы
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/delivery', [PageController::class, 'delivery'])->name('delivery');
Route::get('/contacts', [PageController::class, 'contacts'])->name('contacts');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Личный кабинет (только для авторизованных)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
    Route::get('/profile/orders/{order}', [ProfileController::class, 'showOrder'])->name('profile.order');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.add');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.remove');
});

// Отзывы (для пользователей)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Админ-панель (только для админов)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Заказы
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

    // Товары
    Route::resource('products', ProductController::class);
    
    // Категории
    Route::resource('categories', CategoryController::class)->except(['show']);
    
    // Удаление изображений товара
    Route::delete('/product-images/{image}', [ProductController::class, 'destroyImage'])->name('products.delete-image');
    
    // ОТЗЫВЫ - используем полное имя класса с обратным слешем
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
});