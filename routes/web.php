<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

// Helper to handle legacy controllers that echo output
function legacyCall($controllerClass, $method, ...$args) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $controller = new $controllerClass();
    ob_start();
    $result = $controller->$method(...$args);
    $output = ob_get_clean();
    return $output ?: $result;
}

// Define all routes in a function to avoid duplication
$defineRoutes = function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/products', [HomeController::class, 'index']);

    // Auth Routes
    Route::get('/login', fn() => legacyCall(\App\Controllers\AuthController::class, 'showLogin'));
    Route::post('/login', fn() => legacyCall(\App\Controllers\AuthController::class, 'login'));
    Route::post('/otp/request', fn() => legacyCall(\App\Controllers\AuthController::class, 'requestOtp'));
    Route::post('/otp/login', fn() => legacyCall(\App\Controllers\AuthController::class, 'verifyOtpLogin'));
    Route::get('/register', fn() => legacyCall(\App\Controllers\AuthController::class, 'showRegister'));
    Route::post('/register', fn() => legacyCall(\App\Controllers\AuthController::class, 'register'));
    Route::get('/logout', fn() => legacyCall(\App\Controllers\AuthController::class, 'logout'));

    // Profile Routes
    Route::get('/profile', fn() => legacyCall(\App\Controllers\Customer\ProfileController::class, 'index'));
    Route::post('/profile/update', fn() => legacyCall(\App\Controllers\Customer\ProfileController::class, 'update'));

    // Cart & Checkout Routes
    Route::get('/cart', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'index'));
    Route::post('/cart/add', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'add'));
    Route::post('/cart/remove', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'remove'));
    Route::post('/cart/update', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'update'));
    Route::post('/cart/apply_promo', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'applyPromo'));
    Route::post('/cart/remove_promo', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'removePromo'));
    Route::get('/checkout', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'checkout'));
    Route::post('/checkout/process', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'processCheckout'));
    Route::get('/orders', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'orders'));
    Route::get('/orders/cancel', fn() => legacyCall(\App\Controllers\Customer\CartController::class, 'cancelOrder', request('id')));

    // Product View
    Route::get('/product/view/{id}', fn($id) => legacyCall(\App\Controllers\Customer\ProductController::class, 'view', $id));
    Route::post('/product/review/{id}', fn($id) => legacyCall(\App\Controllers\Customer\ProductController::class, 'addReview', $id));
    Route::post('/product/react/{id}', fn($id) => legacyCall(\App\Controllers\Customer\ProductController::class, 'reactReview', $id));
    Route::post('/product/reply/{id}', fn($id) => legacyCall(\App\Controllers\Customer\ProductController::class, 'replyReview', $id));

    // Payment Routes
    Route::get('/payment/view/{id}', fn($id) => legacyCall(\App\Controllers\Customer\PaymentController::class, 'show', $id));
    Route::post('/payment/upload/{id}', fn($id) => legacyCall(\App\Controllers\Customer\PaymentController::class, 'uploadSlip', $id));

    // Admin Routes
    Route::prefix('admin')->group(function() {
        Route::get('/', fn() => legacyCall(\App\Controllers\Admin\DashboardController::class, 'index'));
        Route::get('/products', fn() => legacyCall(\App\Controllers\Admin\ProductController::class, 'index'));
        Route::get('/products/add', fn() => legacyCall(\App\Controllers\Admin\ProductController::class, 'add'));
        Route::post('/products/save', fn() => legacyCall(\App\Controllers\Admin\ProductController::class, 'save'));
        Route::get('/products/edit/{id}', fn($id) => legacyCall(\App\Controllers\Admin\ProductController::class, 'edit', $id));
        Route::post('/products/update/{id}', fn($id) => legacyCall(\App\Controllers\Admin\ProductController::class, 'update', $id));
        Route::get('/products/delete/{id}', fn($id) => legacyCall(\App\Controllers\Admin\ProductController::class, 'delete', $id));
        Route::get('/promocodes', fn() => legacyCall(\App\Controllers\Admin\PromoCodeController::class, 'index'));
        Route::get('/promocodes/add', fn() => legacyCall(\App\Controllers\Admin\PromoCodeController::class, 'add'));
        Route::post('/promocodes/save', fn() => legacyCall(\App\Controllers\Admin\PromoCodeController::class, 'save'));
        Route::get('/promocodes/edit/{id}', fn($id) => legacyCall(\App\Controllers\Admin\PromoCodeController::class, 'edit', $id));
        Route::post('/promocodes/update/{id}', fn($id) => legacyCall(\App\Controllers\Admin\PromoCodeController::class, 'update', $id));
        Route::get('/promocodes/delete/{id}', fn($id) => legacyCall(\App\Controllers\Admin\PromoCodeController::class, 'delete', $id));
        Route::get('/orders', fn() => legacyCall(\App\Controllers\Admin\OrderController::class, 'index'));
        Route::get('/orders/view/{id}', fn($id) => legacyCall(\App\Controllers\Admin\OrderController::class, 'view', $id));
        Route::post('/orders/update_status/{id}', fn($id) => legacyCall(\App\Controllers\Admin\OrderController::class, 'updateStatus', $id));
        Route::get('/orders/approve_payment/{id}', fn($id) => legacyCall(\App\Controllers\Admin\OrderController::class, 'approvePayment', $id));
        Route::get('/orders/reject_payment/{id}', fn($id) => legacyCall(\App\Controllers\Admin\OrderController::class, 'rejectPayment', $id));
        Route::get('/reviews', fn() => legacyCall(\App\Controllers\Admin\ReviewController::class, 'index'));
        Route::get('/reviews/delete/{id}', fn($id) => legacyCall(\App\Controllers\Admin\ReviewController::class, 'delete', $id));
        Route::get('/banners', fn() => legacyCall(\App\Controllers\Admin\BannerController::class, 'index'));
        Route::get('/banners/add', fn() => legacyCall(\App\Controllers\Admin\BannerController::class, 'create'));
        Route::post('/banners/save', fn() => legacyCall(\App\Controllers\Admin\BannerController::class, 'save'));
        Route::get('/banners/edit/{id}', fn($id) => legacyCall(\App\Controllers\Admin\BannerController::class, 'edit', $id));
        Route::get('/banners/delete/{id}', fn($id) => legacyCall(\App\Controllers\Admin\BannerController::class, 'delete', $id));
    });

    // Chatbot API
    Route::get('/api/chat', fn() => legacyCall(\App\Controllers\Customer\ChatbotController::class, 'chat'));
};


// Apply routes normally
$defineRoutes();

// Also apply routes with index.php prefix for legacy link compatibility
Route::prefix('index.php')->group($defineRoutes);


