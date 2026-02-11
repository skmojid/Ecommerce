<?php
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Shop\AccountController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\OrderController;
use App\Http\Controllers\Shop\ShopController;
use Illuminate\Support\Facades\Route;
Route::get('/test-session', function () {
    if (session()->isStarted()) {
        return 'Session is working: ' . session()->getId();
    } else {
        return 'Session not started';
    }
})->name('test.session');
Route::get('/logout-test', function () {
    return view('logout-test');
})->name('logout.test');
Route::get('/cart-test', function () {
    return view('cart-debug');
})->name('cart.test');
Route::middleware(['web', 'guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/admin-login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
    Route::post('/admin-login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
    Route::get('/minimal-working-login', [AuthController::class, 'showMinimalWorkingLogin'])->name('minimal.working.login');
    Route::post('/minimal-working-login', [AuthController::class, 'minimalWorkingLogin'])->name('minimal.working.login.submit');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware(['web', 'auth']);
Route::get('/test-images', function() {
    return view('test-images');
});
Route::get('/', [ShopController::class, 'index'])->name('shop.index');
Route::get('/search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/products/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/categories', [ShopController::class, 'categories'])->name('shop.categories.index');
Route::get('/category/{category}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/contact', [ShopController::class, 'contact'])->name('contact');
Route::post('/contact', [ShopController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/cart/totals', [CartController::class, 'totals'])->name('cart.totals');
Route::middleware(['web'])->group(function () {
    Route::get('/profile', [AccountController::class, 'index'])->name('profile');
    Route::put('/profile', [AccountController::class, 'update'])->name('profile.update');
});
Route::middleware(['web', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::get('/account/password', [AccountController::class, 'showPasswordForm'])->name('account.password');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');
});