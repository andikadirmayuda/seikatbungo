
<?php

use App\Http\Controllers\OrderCustomBouquetController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OnlineCustomerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PublicInvoiceController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\ArchiveSettingController;
use App\Http\Controllers\HistorySettingController;
use App\Http\Controllers\PublicSaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\AdminPublicOrderController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\BouquetController;
use App\Http\Controllers\BouquetCategoryController;
use App\Http\Controllers\BouquetSizeController;
use App\Http\Controllers\BouquetComponentController;
use App\Http\Controllers\PublicCartController;
use App\Http\Controllers\PublicCheckoutController;


use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('public.flowers');
});

// Public API Routes untuk validasi kode reseller
Route::post('/api/validate-reseller-code', [OnlineCustomerController::class, 'validateResellerCode'])->name('api.validate-reseller-code');
Route::post('/api/mark-reseller-code-used', [OnlineCustomerController::class, 'markResellerCodeUsed'])->name('api.mark-reseller-code-used');

use App\Http\Controllers\Api\NotificationController as ApiNotificationController;

// Push Notification API Routes
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/api/notifications/pending', [ApiNotificationController::class, 'getPendingNotifications'])
        ->name('api.notifications.pending');
    Route::post('/api/notifications/{id}/delivered', [ApiNotificationController::class, 'markAsDelivered'])
        ->name('api.notifications.delivered');
    Route::post('/api/notifications/test', [ApiNotificationController::class, 'testNotification'])
        ->name('api.notifications.test');
});
// Include notification routes
require __DIR__ . '/notification.php';

// Voucher validation route
Route::post('/voucher/validate', [VoucherController::class, 'validate'])->name('voucher.validate');
Route::post('/checkout/remove-voucher', [PublicCheckoutController::class, 'removeVoucher'])->name('checkout.remove-voucher');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin Voucher Routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('vouchers', AdminVoucherController::class);
    });

    // User Management Routes - Only accessible by owner and admin
    Route::middleware('role:owner,admin')->group(function () {
        Route::resource('users', UserController::class);
    });

    // Customer Management Routes (DEPRECATED - Gunakan online-customers)
    // Route::resource('customers', CustomerController::class);

    // Online Customer Management Routes
    Route::resource('online-customers', OnlineCustomerController::class)->parameters([
        'online-customers' => 'wa_number'
    ]);
    Route::post('online-customers/{wa_number}/set-reseller', [OnlineCustomerController::class, 'setAsReseller'])->name('online-customers.set-reseller');
    Route::post('online-customers/{wa_number}/set-promo', [OnlineCustomerController::class, 'setPromoDiscount'])->name('online-customers.set-promo');

    // Reseller Code Management Routes
    Route::post('online-customers/{wa_number}/generate-code', [OnlineCustomerController::class, 'generateResellerCode'])->name('online-customers.generate-code');
    Route::delete('online-customers/{wa_number}/revoke-code/{codeId}', [OnlineCustomerController::class, 'revokeResellerCode'])->name('online-customers.revoke-code');

    // Customer Trash Routes (DEPRECATED)
    // Route::get('customers/trashed', [CustomerController::class, 'trashed'])->name('customers.trashed');
    // Route::patch('customers/{id}/restore', [CustomerController::class, 'restore'])->name('customers.restore');
    // Route::delete('customers/{id}/force-delete', [CustomerController::class, 'forceDelete'])->name('customers.force-delete');

    // Product Management Routes
    Route::resource('categories', CategoryController::class);


    // Ekspor & Impor Produk Excel
    // Export/Import Produk via JSON
    Route::get('products/export-json', [App\Http\Controllers\ProductJsonController::class, 'export'])->name('products.export-json');
    Route::post('products/import-json', [App\Http\Controllers\ProductJsonController::class, 'import'])->name('products.import-json');
    Route::resource('products', ProductController::class);

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/{product}/history', [InventoryController::class, 'history'])->name('inventory.history');
    Route::get('/inventory/adjust', [InventoryController::class, 'adjustForm'])->name('inventory.adjust.form');
    Route::get('/inventory/{product}/adjust', [InventoryController::class, 'adjustForm'])->name('inventory.adjust-form');
    Route::post('/inventory/{product}/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

    // API untuk modal penyesuaian stok
    Route::get('/api/categories/{category}/products', [App\Http\Controllers\ProductController::class, 'apiByCategory']);
    Route::get('/api/products/{product}/stock', [App\Http\Controllers\ProductController::class, 'apiStock']);

    // Sales Routes
    Route::resource('sales', App\Http\Controllers\SaleController::class)->names([
        'index' => 'sales.index',
        'create' => 'sales.create',
        'store' => 'sales.store',
        'show' => 'sales.show',
        'destroy' => 'sales.destroy',
    ]);
    Route::post('/sales/bulk-delete', [App\Http\Controllers\SaleController::class, 'bulkDelete'])->name('sales.bulk-delete');
    Route::get('/sales/{sale}/download-pdf', [App\Http\Controllers\SaleController::class, 'downloadPdf'])->name('sales.download_pdf');

    // Settings Routes
    Route::prefix('settings')->name('settings.')->middleware(['auth'])->group(function () {
        // Archive Settings
        Route::get('/archive', [ArchiveSettingController::class, 'index'])->name('archive');
        Route::post('/archive', [ArchiveSettingController::class, 'update'])->name('archive.update');
    });
    Route::get('customers/{id}/history', [CustomerController::class, 'orderHistory'])->name('customers.history');
});

// Public Invoice Route (No Auth Required)
Route::get('/i/{token}', [PublicInvoiceController::class, 'show'])->name('public.invoice');

Route::get('/public/receipt/{public_code}', [PublicSaleController::class, 'show'])->name('sales.public_receipt');

// Report Routes - Tanpa middleware, dapat diakses publik
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    Route::get('/orders', [ReportController::class, 'orders'])->name('orders'); // laporan pemesanan
    Route::get('/customers', [ReportController::class, 'customers'])->name('customers'); // laporan pelanggan
    Route::get('/income', [ReportController::class, 'income'])->name('income'); // laporan pendapatan

    // PDF Export Routes
    Route::get('/sales/pdf', [ReportController::class, 'salesPdf'])->name('sales.pdf');
    Route::get('/stock/pdf', [ReportController::class, 'stockPdf'])->name('stock.pdf');
    Route::get('/orders/pdf', [ReportController::class, 'ordersPdf'])->name('orders.pdf');
    Route::get('/income/pdf', [ReportController::class, 'incomePdf'])->name('income.pdf');

    // Route berikut bisa diaktifkan jika fitur Excel sudah tersedia
    // Route::get('/sales/excel', [ReportController::class, 'salesExcel'])->name('sales.excel');
});


// Admin Public Order Routes - Bisa diakses semua user yang login
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/public-orders', [AdminPublicOrderController::class, 'index'])->name('admin.public-orders.index');
    Route::get('/admin/public-orders/filter', [AdminPublicOrderController::class, 'filter'])->name('admin.public-orders.filter');
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/admin/public-orders/{id}', [AdminPublicOrderController::class, 'show'])->name('admin.public-orders.show');
        Route::get('/admin/public-orders/{id}/edit', [AdminPublicOrderController::class, 'edit'])->name('admin.public-orders.edit');
        Route::put('/admin/public-orders/{id}', [AdminPublicOrderController::class, 'update'])->name('admin.public-orders.update');
    });
    Route::post('/admin/public-orders/mass-delete', [AdminPublicOrderController::class, 'massDelete'])->name('admin.public-orders.mass-delete');
    Route::post('/admin/public-orders/bulk-delete', [AdminPublicOrderController::class, 'bulkDelete'])->name('admin.public-orders.bulk-delete');
});

// Route untuk invoice publik khusus pemesanan publik (PUBLIC, tanpa auth)
Route::get('/invoice/{public_code}', [App\Http\Controllers\PublicOrderController::class, 'publicInvoice'])->name('public.order.invoice');

// Public Order (API) - untuk form pemesanan publik
Route::post('/public-order', [App\Http\Controllers\PublicOrderController::class, 'store']);
// Pembayaran DP atau pelunasan public order
Route::post('/public-order/{public_code}/pay', [App\Http\Controllers\PublicOrderController::class, 'pay'])->name('public.order.pay');
Route::post('/admin/public-orders/{id}/update-status', [App\Http\Controllers\AdminPublicOrderController::class, 'updateStatus'])->name('admin.public-orders.update-status');
Route::post('/admin/public-orders/{id}/update-payment-status', [App\Http\Controllers\AdminPublicOrderController::class, 'updatePaymentStatus'])->name('admin.public-orders.update-payment-status');
Route::post('/admin/public-orders/{id}/update-shipping-fee', [App\Http\Controllers\AdminPublicOrderController::class, 'updateShippingFee'])->name('admin.public-orders.update-shipping-fee');
Route::get('/admin/public-orders/{id}/whatsapp-message', [App\Http\Controllers\AdminPublicOrderController::class, 'generateWhatsAppMessage'])->name('admin.public-orders.whatsapp-message');
Route::get('/admin/public-orders/{id}/customer-link-message', [App\Http\Controllers\AdminPublicOrderController::class, 'generateCustomerLinkMessage'])->name('admin.public-orders.customer-link-message');

// =====================
// Route test upload sederhana
Route::get('/test-upload', function () {
    return '<form method="POST" action="/test-upload" enctype="multipart/form-data">'
        . csrf_field() .
        '<input type="file" name="testfile" />'
        . '<button type="submit">Upload</button>'
        . '</form>';
});

Route::post('/test-upload', function (\Illuminate\Http\Request $request) {
    if ($request->hasFile('testfile')) {
        $file = $request->file('testfile');
        if (!$file->isValid()) {
            return 'File upload tidak valid!';
        }
        $path = $file->store('public/packing_photos');
        return 'Upload berhasil! Path: ' . $path;
    }
    return 'Tidak ada file terupload!';
});


// Endpoint untuk mengambil isi cart dalam format JSON
Route::get('/cart/json', [PublicCartController::class, 'getCart'])->name('public.cart.json');
Route::get('/cart/get', [PublicCartController::class, 'getCart']); // Alias
Route::get('/cart/items', [PublicCartController::class, 'getCart']); // Alias for backward compatibility

// Route::post('/admin/public-orders/{id}/add-payment', [AdminPublicOrderController::class, 'addPayment'])->name('admin.public-orders.add-payment'); // TIDAK DIGUNAKAN LAGI

// Route resource untuk Pesanan & Penjualan Buket
Route::resource('bouquets', BouquetController::class);
Route::resource('bouquet-categories', BouquetCategoryController::class);
Route::resource('bouquet-sizes', BouquetSizeController::class);
Route::get('bouquet-components/manage/{bouquet}/{size}', [App\Http\Controllers\BouquetComponentController::class, 'manage'])->name('bouquet-components.manage');
Route::resource('bouquet-components', BouquetComponentController::class);

// Voucher validation route
Route::post('/voucher/validate', [VoucherController::class, 'validate'])->name('voucher.validate');


require __DIR__ . '/auth.php';
