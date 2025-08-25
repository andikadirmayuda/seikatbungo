<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicFlowerController;
use App\Http\Controllers\PublicOrderController;
use App\Http\Controllers\PublicCheckoutController;
use App\Http\Controllers\PublicCartController;
use App\Http\Controllers\PublicBouquetController;
use App\Http\Controllers\CustomBouquetController;

Route::get('/product-seikatbungo', [PublicFlowerController::class, 'index'])->name('public.flowers');
Route::get('/bouquet-seikatbungo', [PublicBouquetController::class, 'index'])->name('public.bouquets');
Route::get('/bouquet/{id}', [PublicBouquetController::class, 'detail'])->name('public.bouquet.detail');
Route::get('/bouquet/{id}/detail-json', [PublicBouquetController::class, 'detailJson'])->name('public.bouquet.detail.json');
Route::get('/bouquet/{bouquetId}/components/{sizeId}', [PublicBouquetController::class, 'getComponentsBySize'])->name('public.bouquet.components.by.size');
Route::post('/public-order', [PublicOrderController::class, 'store'])->name('public.order.store');

// Custom Bouquet routes
Route::get('/custom-bouquet/create', [CustomBouquetController::class, 'create'])->name('custom.bouquet.create');
Route::get('/custom-bouquet/{customBouquet}/details', [CustomBouquetController::class, 'getDetails'])->name('custom.bouquet.details');
Route::get('/product/{product}/details', [CustomBouquetController::class, 'getProductDetails'])->name('product.details');
Route::post('/custom-bouquet/add-item', [CustomBouquetController::class, 'addItem'])->name('custom.bouquet.add-item');
Route::post('/custom-bouquet/remove-item', [CustomBouquetController::class, 'removeItem'])->name('custom.bouquet.remove-item');
Route::post('/custom-bouquet/update-item', [CustomBouquetController::class, 'updateItem'])->name('custom.bouquet.update-item');
Route::post('/custom-bouquet/{id}/ribbon', [CustomBouquetController::class, 'updateRibbon'])->name('custom.bouquet.update-ribbon');
Route::post('/custom-bouquet/clear', [CustomBouquetController::class, 'clear'])->name('custom.bouquet.clear');
Route::post('/custom-bouquet/upload-reference', [CustomBouquetController::class, 'uploadReference'])->name('custom.bouquet.upload-reference');
Route::post('/custom-bouquet/{id}/finalize', [CustomBouquetController::class, 'finalize'])->name('custom.bouquet.finalize');
Route::post('/custom-bouquet/{customBouquet}/add-to-cart', [CustomBouquetController::class, 'addToCart'])->name('custom.bouquet.add-to-cart');

// Public order detail only (view only)
Route::get('/public-order/{public_code}', [PublicOrderController::class, 'show'])->name('public.order.show');

// Checkout routes
Route::get('/checkout', [PublicCheckoutController::class, 'show'])->name('public.checkout');
Route::post('/checkout', [PublicCheckoutController::class, 'process'])->name('public.checkout.process');

// Cart routes (agar keranjang dan checkout konsisten di public)
Route::get('/cart', [PublicCartController::class, 'index'])->name('public.cart.index');
Route::post('/cart/add', [PublicCartController::class, 'add'])->name('public.cart.add');
Route::post('/cart/add-bouquet', [PublicCartController::class, 'addBouquet'])->name('public.cart.add-bouquet');
Route::post('/cart/add-custom-bouquet', [PublicCartController::class, 'addCustomBouquet'])->name('public.cart.add-custom-bouquet');
Route::post('/cart/update/{cartKey}', [PublicCartController::class, 'updateQuantity'])->name('public.cart.update');
Route::post('/cart/remove/{cartKey}', [PublicCartController::class, 'remove'])->name('public.cart.remove');
Route::post('/cart/clear', [PublicCartController::class, 'clear'])->name('public.cart.clear');
Route::get('/cart/get', [PublicCartController::class, 'getCart']);
Route::get('/cart/items', [PublicCartController::class, 'getCart']); // Alias for backward compatibility

// Invoice publik (detail pesanan publik)
Route::get('/invoice/{public_code}', [PublicOrderController::class, 'publicInvoice'])
    ->middleware('throttle:10,1') // Maksimal 10x akses per menit per IP
    ->name('public.order.invoice');

// Halaman detail pemesanan publik (tracking)
Route::get('/order/{public_code}', [PublicOrderController::class, 'publicOrderDetail'])
    ->middleware('throttle:10,1') // Maksimal 10x akses per menit per IP
    ->name('public.order.detail');

// Tracking pesanan publik berdasarkan nomor WhatsApp
Route::get('/track-order', [App\Http\Controllers\PublicOrderController::class, 'trackOrderForm'])->name('public.order.track');
