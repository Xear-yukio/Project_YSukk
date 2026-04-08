<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockRequestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    $allProducts = \App\Models\Product::all();
    $flashSales = $allProducts->take(4);
    $bestSellers = $allProducts->skip(4)->take(5);
    $ourProducts = $allProducts->take(8);
    $newArrivals = \App\Models\Product::where('badge', 'NEW')->orderBy('created_at', 'desc')->take(4)->get();
    
    return view('welcome', compact('flashSales', 'bestSellers', 'ourProducts', 'newArrivals'));
})->name('home');

// Guest routes (login & register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Shared Admin & Petugas routes
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
        Route::post('/admin/products', [AdminController::class, 'productStore'])->name('admin.products.store');
        Route::put('/admin/products/{id}', [AdminController::class, 'productUpdate'])->name('admin.products.update');
        Route::delete('/admin/products/{id}', [AdminController::class, 'productDelete'])->name('admin.products.delete');
        Route::get('/admin/promos', [AdminController::class, 'promos'])->name('admin.promos');
        Route::put('/admin/promos/{id}', [AdminController::class, 'promoUpdate'])->name('admin.promos.update');
        Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
        Route::post('/admin/categories', [AdminController::class, 'categoryStore'])->name('admin.categories.store');
        Route::put('/admin/categories/{id}', [AdminController::class, 'categoryUpdate'])->name('admin.categories.update');
        Route::delete('/admin/categories/{id}', [AdminController::class, 'categoryDelete'])->name('admin.categories.delete');
        Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
        Route::get('/admin/orders/{id}', [AdminController::class, 'orderDetail'])->name('admin.orders.show');
        Route::post('/admin/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.updateStatus');
        Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');

        // Stock Requests
        Route::get('/admin/stock-requests', [StockRequestController::class, 'index'])->name('admin.stock_requests');
        Route::post('/admin/stock-requests', [StockRequestController::class, 'store'])->name('admin.stock_requests.store');
        Route::put('/admin/stock-requests/{id}', [StockRequestController::class, 'update'])->name('admin.stock_requests.update');

        // Review Management
        Route::get('/admin/reviews', [\App\Http\Controllers\ReviewController::class, 'adminIndex'])->name('admin.reviews');
        Route::post('/admin/reviews/{review}/like', [\App\Http\Controllers\ReviewController::class, 'toggleLike'])->name('admin.reviews.like');

        // Message Management
        Route::get('/admin/messages', [\App\Http\Controllers\AdminMessageController::class, 'index'])->name('admin.messages');
        Route::get('/admin/messages/{conversation}', [\App\Http\Controllers\AdminMessageController::class, 'show'])->name('admin.messages.show');
        Route::post('/admin/messages/{conversation}/reply', [\App\Http\Controllers\AdminMessageController::class, 'reply'])->name('admin.messages.reply');
    });

    // Admin-only Dashboard & Sensitive routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [DashboardController::class, 'adminIndex'])->name('admin.dashboard');
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users', [AdminController::class, 'userStore'])->name('admin.users.store');
        Route::put('/admin/users/{id}', [AdminController::class, 'userUpdate'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AdminController::class, 'userDelete'])->name('admin.users.delete');
        Route::get('/admin/reports', [AdminController::class, 'reports'])->name('admin.reports');
    });

    // Petugas Dashboard
    Route::middleware('role:petugas')->group(function () {
        Route::get('/petugas/dashboard', [DashboardController::class, 'petugasIndex'])->name('petugas.dashboard');
    });

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/confirm/{order}', [CheckoutController::class, 'confirmPayment'])->name('checkout.confirm');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');

    // Orders
    Route::get('/orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');

    // Profile / Settings
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Messages / Inbox (User)
    Route::get('/inbox', [\App\Http\Controllers\MessageController::class, 'index'])->name('messages.index');
    Route::get('/inbox/{conversation}', [\App\Http\Controllers\MessageController::class, 'show'])->name('messages.show');
    Route::post('/inbox/{conversation}/reply', [\App\Http\Controllers\MessageController::class, 'reply'])->name('messages.reply');
    Route::post('/kontak/send', [\App\Http\Controllers\MessageController::class, 'store'])->name('messages.store');

    Route::post('/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
});

// Product Detail & Category
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category.show');
Route::get('/terbaru', [ProductController::class, 'newProducts'])->name('products.new');
Route::get('/promo', [ProductController::class, 'promoProducts'])->name('products.promo');
Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/api/products', [ProductController::class, 'apiIndex'])->name('api.products.index');

// Static Pages
Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
