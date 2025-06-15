<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\ShipmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Thay thế route mặc định
Route::get('/', function () {
    // Nếu người dùng đã đăng nhập, chuyển hướng đến dashboard
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    // Nếu chưa, hiển thị trang welcome tùy chỉnh
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Products
    Route::resource('products', ProductController::class);

    // Warehouses
    Route::resource('warehouses', WarehouseController::class);

    // Inventory
    Route::resource('inventory', InventoryController::class);
    Route::post('/inventory/{product}/add/{warehouse}', [InventoryController::class, 'addStock'])->name('inventory.addStock');
    Route::post('/inventory/{product}/remove/{warehouse}', [InventoryController::class, 'removeStock'])->name('inventory.removeStock');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/complete', [OrderController::class, 'completeOrder'])->name('orders.complete');
    Route::post('orders/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');

    // Vehicles
    Route::resource('vehicles', VehicleController::class);

    // Shipments
    Route::resource('shipments', ShipmentController::class);

    // Reports
    Route::get('/reports/revenue', [ReportController::class, 'revenueReport'])->name('reports.revenue');
    Route::get('/reports/inventory', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
    Route::get('/reports/shipment', [ReportController::class, 'shipmentReport'])->name('reports.shipment');
});

require __DIR__.'/auth.php';