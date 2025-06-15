<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Shipment; // Thêm dòng này
use App\Models\Vehicle; // Thêm dòng này
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenueReport(Request $request)
    {
        $period = $request->get('period', 'daily'); // 'daily', 'weekly', 'monthly', 'yearly'
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Nếu không có ngày bắt đầu/kết thúc, mặc định lấy dữ liệu 30 ngày gần nhất
        if (empty($startDate) && empty($endDate)) {
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(30);
        } elseif (empty($startDate)) {
            $endDate = Carbon::parse($endDate);
            $startDate = $endDate->copy()->subDays(30); // Mặc định 30 ngày trước nếu chỉ có end_date
        } elseif (empty($endDate)) {
            $startDate = Carbon::parse($startDate);
            $endDate = $startDate->copy()->addDays(30); // Mặc định 30 ngày sau nếu chỉ có start_date
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Đảm bảo startDate không lớn hơn endDate
        if ($startDate->greaterThan($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $orders = Order::whereBetween('order_date', [$startDate, $endDate])
                        ->where('status', 'completed') // Chỉ lấy đơn hàng đã hoàn thành
                        ->orderBy('order_date')
                        ->get();

        $reportData = [];

        foreach ($orders as $order) {
            $date = Carbon::parse($order->order_date);
            $key = '';

            switch ($period) {
                case 'daily':
                    $key = $date->format('Y-m-d'); // Ví dụ: 2023-10-27
                    break;
                case 'weekly':
                    $key = $date->startOfWeek()->format('Y-m-d') . ' - ' . $date->endOfWeek()->format('Y-m-d'); // Ví dụ: 2023-10-23 - 2023-10-29
                    break;
                case 'monthly':
                    $key = $date->format('Y-m'); // Ví dụ: 2023-10
                    break;
                case 'yearly':
                    $key = $date->format('Y'); // Ví dụ: 2023
                    break;
            }

            if (!isset($reportData[$key])) {
                $reportData[$key] = 0;
            }
            $reportData[$key] += $order->total_amount;
        }

        // Sắp xếp dữ liệu theo khóa (ngày/tuần/tháng/năm) để đảm bảo hiển thị đúng thứ tự
        ksort($reportData);

        // Chuẩn bị dữ liệu cho biểu đồ (nếu cần) và bảng
        $labels = array_keys($reportData);
        $data = array_values($reportData);

        return view('reports.revenue_report', [
            'labels' => $labels,
            'data' => $data,
            'reportData' => $reportData, // Dữ liệu chi tiết cho bảng
            'period' => $period,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
        ]);
    }

    public function inventoryReport(Request $request)
    {
        $products = Product::with(['inventories' => function($query) {
            $query->with('warehouse'); // Tải thông tin kho liên quan đến tồn kho
        }])->get();

        $warehouses = Warehouse::all();

        // Chuẩn bị dữ liệu để dễ dàng hiển thị trong bảng
        $reportData = [];
        foreach ($products as $product) {
            $productData = [
                'name' => $product->name,
                'sku' => $product->sku,
                'unit' => $product->unit,
                'total_stock' => 0,
                'warehouse_stocks' => []
            ];

            foreach ($warehouses as $warehouse) {
                // Tìm số lượng tồn kho của sản phẩm này tại kho này
                $stock = $product->inventories->where('warehouse_id', $warehouse->id)->first();
                $quantity = $stock ? $stock->quantity : 0;
                $productData['warehouse_stocks'][$warehouse->id] = $quantity;
                $productData['total_stock'] += $quantity;
            }
            $reportData[] = $productData;
        }

        return view('reports.inventory_report', [
            'reportData' => $reportData,
            'warehouses' => $warehouses, // Truyền danh sách các kho để làm header bảng
        ]);
    }

    public function shipmentReport(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $status = $request->get('status');
        $vehicleId = $request->get('vehicle_id');

        // Mặc định 30 ngày gần nhất nếu không có ngày được chọn
        if (empty($startDate) && empty($endDate)) {
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(30);
        } elseif (empty($startDate)) {
            $endDate = Carbon::parse($endDate);
            $startDate = $endDate->copy()->subDays(30);
        } elseif (empty($endDate)) {
            $startDate = Carbon::parse($startDate);
            $endDate = $startDate->copy()->addDays(30);
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Đảm bảo startDate không lớn hơn endDate
        if ($startDate->greaterThan($endDate)) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        $shipmentsQuery = Shipment::with(['order.customer', 'vehicle', 'originWarehouse', 'destinationWarehouse'])
                                    ->whereBetween('shipment_date', [$startDate, $endDate])
                                    ->orderBy('shipment_date', 'desc');

        if ($status) {
            $shipmentsQuery->where('status', $status);
        }

        if ($vehicleId) {
            $shipmentsQuery->where('vehicle_id', $vehicleId);
        }

        $shipments = $shipmentsQuery->get();

        // Thống kê theo trạng thái
        $statusCounts = $shipments->groupBy('status')->map->count();

        // Thống kê theo phương tiện
        $vehicleCounts = $shipments->groupBy(function($item) {
            return $item->vehicle_id ?? 'no_vehicle';
        })->map(function($group, $key) {
            if ($key === 'no_vehicle') {
                return ['name' => 'Chưa gán phương tiện', 'count' => $group->count()];
            }
            return ['name' => $group->first()->vehicle->license_plate ?? 'Không xác định', 'count' => $group->count()];
        })->sortByDesc('count');

        $allVehicles = Vehicle::all();
        $shipmentStatuses = ['pending', 'in_transit', 'delivered', 'cancelled']; // Các trạng thái có thể có

        return view('reports.shipment_report', [
            'shipments' => $shipments,
            'statusCounts' => $statusCounts,
            'vehicleCounts' => $vehicleCounts,
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d'),
            'selectedStatus' => $status,
            'selectedVehicleId' => $vehicleId,
            'allVehicles' => $allVehicles,
            'shipmentStatuses' => $shipmentStatuses,
        ]);
    }
}