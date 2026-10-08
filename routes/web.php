<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Consolidated RESTful web routes for the inventory management system.
| All endpoints map to dedicated controllers following PSR-12 and Laravel
| best practices.
|
*/

// Dashboard & General Overview
Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
Route::get('/dashboard', [InventoryController::class, 'index'])->name('dashboard');
Route::get('/inventory', [InventoryController::class, 'overview'])->name('inventory.overview');

// Product Management
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/lookup/{sku}', [ProductController::class, 'lookup'])->name('products.lookup');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

// Stock Movements & Audits
Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::post('/stock/in', [StockController::class, 'stockIn'])->name('stock.in');
Route::post('/stock/out', [StockController::class, 'stockOut'])->name('stock.out');
Route::post('/stock/{product}/reorder', [StockController::class, 'reorder'])->name('stock.reorder');

// Suppliers
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

// Reports & Analytics
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Settings
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

// Notifications JSON feed (for topbar panel)
Route::get('/api/notifications', [NotificationController::class, 'index'])->name('notifications.index');
