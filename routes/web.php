<?php

use App\Http\Controllers\ParcelController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', [HomeController::class, 'index'])->name('landing');

require __DIR__.'/auth.php';

// ─── ADMIN ROUTES ─────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Parcels
    Route::get('/parcels', [AdminController::class, 'parcels'])->name('parcels');
    Route::get('/parcels/create', [AdminController::class, 'createParcel'])->name('parcel-form');
    Route::post('/parcels', [AdminController::class, 'storeParcel'])->name('parcels.store');
    Route::get('/parcels/{parcel}/edit', [AdminController::class, 'editParcel'])->name('parcels.edit');
    Route::put('/parcels/{parcel}', [AdminController::class, 'updateParcel'])->name('parcels.update');
    Route::delete('/parcels/{parcel}', [AdminController::class, 'deleteParcel'])->name('parcels.delete');
    Route::patch('/parcels/{parcel}/status', [AdminController::class, 'quickUpdateParcelStatus'])->name('parcels.quick-status');

    // Bookings
    Route::get('/bookings', [AdminController::class, 'bookings'])->name('bookings');
    Route::put('/bookings/{booking}/status', [AdminController::class, 'updateBookingStatus'])->name('bookings.status');
    Route::get('/bookings/{booking}/receipt', [AdminController::class, 'viewReceipt'])->name('bookings.receipt');

    // Shop
    Route::get('/shop-status', [AdminController::class, 'shopStatus'])->name('shop-status');
    Route::post('/shop-status', [AdminController::class, 'updateShopStatus'])->name('shop-status.update');

    // QR Code
    Route::get('/qr-code', [AdminController::class, 'qrCode'])->name('qr-code');
    Route::post('/qr-code', [AdminController::class, 'updateQrCode'])->name('qr-code.update');
    Route::delete('/qr-code', [AdminController::class, 'deleteQrCode'])->name('qr-code.delete');

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'adminIndex'])->name('reviews.index');
    Route::post('/reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');

    // Logo
    Route::post('/logo/update', [AdminController::class, 'updateLogo'])->name('logo.update');

    // Booking Slots
    Route::get('/booking-slots', [AdminController::class, 'bookingSlots'])->name('booking-slots');
    Route::post('/booking-slots', [AdminController::class, 'updateBookingSlots'])->name('booking-slots.update');

    // Uncollected link generator
    Route::post('/uncollected-link', [AdminController::class, 'uncollectedLink'])->name('uncollected-link');
});

// ─── USER ROUTES ──────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [ParcelController::class, 'index'])->name('home');
    Route::get('/search', [ParcelController::class, 'search'])->name('parcels.search');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [BookingController::class, 'checkout'])->name('checkout');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/my-booking', [BookingController::class, 'index'])->name('booking.index');
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{booking}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/contact', [ReviewController::class, 'index'])->name('contact');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// ─── PUBLIC ROUTES ────────────────────────────────────────────
Route::get('/uncollected/{token}', [AdminController::class, 'uncollectedPublic'])->name('uncollected.public');

// About Us — public, tak perlu login
Route::get('/about', function () {
    $totalParcels  = \App\Models\Parcel::count();
    $totalStudents = \App\Models\User::where('is_admin', false)->count();
    $avgRating     = \App\Models\Review::avg('rating') ?? 0;
    return view('about', compact('totalParcels','totalStudents','avgRating'));
})->name('about');