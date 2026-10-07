<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
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

// Stock Movements & Audits
Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
Route::post('/stock/in', [StockController::class, 'stockIn'])->name('stock.in');
Route::post('/stock/out', [StockController::class, 'stockOut'])->name('stock.out');
Route::post('/stock/{product}/reorder', [StockController::class, 'reorder'])->name('stock.reorder');

// Suppliers
Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');

// Reports & Analytics
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

// Settings
Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
