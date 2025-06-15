<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Shipment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Lấy ngày hiện tại và 30 ngày trước để tính toán thống kê trong 30 ngày gần nhất
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(30);

        // Thống kê Đơn hàng
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        // Thống kê Doanh thu (30 ngày gần nhất)
        $revenueLast30Days = Order::where('status', 'completed')
                                   ->whereBetween('order_date', [$startDate, $endDate])
                                   ->sum('total_amount');

        // Thống kê Sản phẩm
        $totalProducts = Product::count();

        // Thống kê Kho hàng
        $totalWarehouses = Warehouse::count();

        // Thống kê Chuyến hàng
        $totalShipments = Shipment::count();
        $pendingShipments = Shipment::where('status', 'pending')->count();
        $inTransitShipments = Shipment::where('status', 'in_transit')->count();
        $deliveredShipments = Shipment::where('status', 'delivered')->count();


        return view('dashboard', compact(
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'revenueLast30Days',
            'totalProducts',
            'totalWarehouses',
            'totalShipments',
            'pendingShipments',
            'inTransitShipments',
            'deliveredShipments'
        ));
    }
}