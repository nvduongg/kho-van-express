<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\Warehouse;
use App\Models\Inventory; // Để quản lý tồn kho
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Để sử dụng Transaction
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ShipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shipment::with(['order.customer', 'vehicle', 'originWarehouse', 'destinationWarehouse']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('order_id')) {
            $query->where('order_id', $request->order_id);
        }
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }
        if ($request->filled('shipment_date_from')) {
            $query->whereDate('shipment_date', '>=', $request->shipment_date_from);
        }
        if ($request->filled('shipment_date_to')) {
            $query->whereDate('shipment_date', '<=', $request->shipment_date_to);
        }

        $shipments = $query->latest()->paginate(10);

        $orders = Order::orderBy('order_date', 'desc')->get();
        $vehicles = Vehicle::orderBy('make')->get();
        $shipmentStatuses = ['pending', 'in_transit', 'delivered', 'cancelled', 'returned'];

        return view('shipments.index', compact('shipments', 'orders', 'vehicles', 'shipmentStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::whereNotIn('status', ['delivered', 'cancelled'])
                        ->orderBy('order_date', 'desc')
                        ->get(); // Chỉ lấy các đơn hàng chưa hoàn thành
        $vehicles = Vehicle::where('status', 'available')->orderBy('make')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        $shipmentStatuses = ['pending', 'in_transit', 'delivered', 'cancelled', 'returned'];

        return view('shipments.create', compact('orders', 'vehicles', 'warehouses', 'shipmentStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => [
                'required',
                'exists:orders,id',
                Rule::unique('shipments')->where(function ($query) use ($request) {
                    return $query->where('order_id', $request->order_id);
                })->ignore($request->shipment_id), // Đảm bảo mỗi đơn hàng chỉ có 1 chuyến hàng
            ],
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'origin_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'nullable|exists:warehouses,id',
            'tracking_number' => 'nullable|string|max:255|unique:shipments',
            'shipment_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:shipment_date',
            'status' => 'required|string|in:pending,in_transit,delivered,cancelled,returned',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $order = Order::find($request->order_id);
            if (!$order) {
                // Đây là fallback vì đã có exists validator
                return back()->withErrors(['order_id' => 'Đơn hàng không tồn tại.']);
            }

            // Tạo mã tracking nếu chưa có
            $trackingNumber = $request->tracking_number ?? 'SHP-' . Carbon::now()->format('YmdHis') . '-' . uniqid();

            $shipment = Shipment::create([
                'order_id' => $request->order_id,
                'vehicle_id' => $request->vehicle_id,
                'origin_warehouse_id' => $request->origin_warehouse_id,
                'destination_warehouse_id' => $request->destination_warehouse_id,
                'tracking_number' => $trackingNumber,
                'shipment_date' => Carbon::parse($request->shipment_date),
                'delivery_date' => $request->delivery_date ? Carbon::parse($request->delivery_date) : null,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Cập nhật trạng thái đơn hàng nếu cần
            if ($request->status == 'in_transit') {
                $order->status = 'processing'; // Hoặc trạng thái phù hợp khi bắt đầu vận chuyển
                $order->save();
            }

            // Xử lý tồn kho: Trừ số lượng sản phẩm trong kho xuất phát
            // Điều này cần được xử lý cẩn thận hơn trong môi trường thực tế (vd: khi xác nhận xuất kho)
            // Hiện tại, ta trừ khi shipment được tạo và có trạng thái phù hợp (vd: in_transit)
            if ($request->status == 'in_transit') {
                foreach ($order->orderItems as $item) {
                    $inventory = Inventory::where('warehouse_id', $request->origin_warehouse_id)
                                          ->where('product_id', $item->product_id)
                                          ->first();

                    if ($inventory) {
                        if ($inventory->quantity >= $item->quantity) {
                            $inventory->quantity -= $item->quantity;
                            $inventory->save();
                        } else {
                            // Xử lý khi không đủ hàng tồn kho
                            // Có thể ném lỗi hoặc ghi log
                            throw new \Exception('Không đủ số lượng ' . $item->product->name . ' trong kho ' . $shipment->originWarehouse->name);
                        }
                    } else {
                        // Xử lý khi không tìm thấy tồn kho cho sản phẩm này trong kho đó
                        throw new \Exception('Không tìm thấy tồn kho cho ' . $item->product->name . ' trong kho ' . $shipment->originWarehouse->name);
                    }
                }
            }
        });

        return redirect()->route('shipments.index')->with('success', 'Chuyến hàng đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shipment $shipment)
    {
        $shipment->load('order.customer', 'order.orderItems.product', 'vehicle', 'originWarehouse', 'destinationWarehouse');
        return view('shipments.show', compact('shipment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shipment $shipment)
    {
        $orders = Order::orderBy('order_date', 'desc')->get();
        $vehicles = Vehicle::orderBy('make')->get();
        $warehouses = Warehouse::orderBy('name')->get();
        $shipmentStatuses = ['pending', 'in_transit', 'delivered', 'cancelled', 'returned'];
        return view('shipments.edit', compact('shipment', 'orders', 'vehicles', 'warehouses', 'shipmentStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'order_id' => [
                'required',
                'exists:orders,id',
                Rule::unique('shipments')->where(function ($query) use ($request, $shipment) {
                    return $query->where('order_id', $request->order_id);
                })->ignore($shipment->id), // Đảm bảo mỗi đơn hàng chỉ có 1 chuyến hàng
            ],
            'vehicle_id' => 'nullable|exists:vehicles,id',
            'origin_warehouse_id' => 'required|exists:warehouses,id',
            'destination_warehouse_id' => 'nullable|exists:warehouses,id',
            'tracking_number' => 'nullable|string|max:255|unique:shipments,tracking_number,' . $shipment->id,
            'shipment_date' => 'required|date',
            'delivery_date' => 'nullable|date|after_or_equal:shipment_date',
            'status' => 'required|string|in:pending,in_transit,delivered,cancelled,returned',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $shipment) {
            // Kiểm tra và hoàn lại tồn kho cũ nếu kho xuất phát thay đổi hoặc trạng thái chuyển từ in_transit sang trạng thái khác
            $oldStatus = $shipment->status;
            $oldOriginWarehouseId = $shipment->origin_warehouse_id;

            $shipment->update([
                'order_id' => $request->order_id,
                'vehicle_id' => $request->vehicle_id,
                'origin_warehouse_id' => $request->origin_warehouse_id,
                'destination_warehouse_id' => $request->destination_warehouse_id,
                'tracking_number' => $request->tracking_number,
                'shipment_date' => Carbon::parse($request->shipment_date),
                'delivery_date' => $request->delivery_date ? Carbon::parse($request->delivery_date) : null,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Logic cập nhật tồn kho phức tạp hơn một chút:
            // 1. Nếu kho xuất phát thay đổi: hoàn lại hàng vào kho cũ, trừ ra từ kho mới.
            // 2. Nếu trạng thái chuyển sang 'delivered' hoặc 'cancelled'/'returned': cập nhật trạng thái đơn hàng.
            // 3. Nếu trạng thái chuyển từ 'in_transit' sang trạng thái không phải 'delivered': hoàn lại tồn kho.
            // 4. Nếu trạng thái chuyển sang 'in_transit': trừ tồn kho.

            $order = $shipment->order;

            // Hoàn lại tồn kho nếu kho xuất phát thay đổi HOẶC trạng thái cũ là 'in_transit' và trạng thái mới không phải 'delivered'
            if (($oldOriginWarehouseId != $request->origin_warehouse_id && $oldStatus == 'in_transit') ||
                ($oldStatus == 'in_transit' && $request->status != 'delivered' && $request->status != 'in_transit')) {
                foreach ($order->orderItems as $item) {
                    $inventory = Inventory::where('warehouse_id', $oldOriginWarehouseId)
                                          ->where('product_id', $item->product_id)
                                          ->first();
                    if ($inventory) {
                        $inventory->quantity += $item->quantity;
                        $inventory->save();
                    }
                }
            }

            // Trừ tồn kho nếu trạng thái mới là 'in_transit' và trạng thái cũ không phải 'in_transit' HOẶC kho xuất phát thay đổi
            if ($request->status == 'in_transit' && ($oldStatus != 'in_transit' || $oldOriginWarehouseId != $request->origin_warehouse_id)) {
                foreach ($order->orderItems as $item) {
                    $inventory = Inventory::where('warehouse_id', $request->origin_warehouse_id)
                                          ->where('product_id', $item->product_id)
                                          ->first();
                    if ($inventory) {
                        if ($inventory->quantity >= $item->quantity) {
                            $inventory->quantity -= $item->quantity;
                            $inventory->save();
                        } else {
                            throw new \Exception('Không đủ số lượng ' . $item->product->name . ' trong kho ' . $shipment->originWarehouse->name . ' để cập nhật chuyến hàng.');
                        }
                    } else {
                        throw new \Exception('Không tìm thấy tồn kho cho ' . $item->product->name . ' trong kho ' . $shipment->originWarehouse->name . ' để cập nhật chuyến hàng.');
                    }
                }
            }

            // Cập nhật trạng thái đơn hàng dựa trên trạng thái chuyến hàng
            if ($request->status == 'delivered') {
                $order->status = 'delivered';
                $order->save();
            } elseif ($request->status == 'cancelled') {
                $order->status = 'cancelled';
                $order->save();
                // Hoàn lại tồn kho nếu bị hủy sau khi đã trừ
                if ($oldStatus == 'in_transit') {
                    foreach ($order->orderItems as $item) {
                        $inventory = Inventory::where('warehouse_id', $shipment->origin_warehouse_id)
                                              ->where('product_id', $item->product_id)
                                              ->first();
                        if ($inventory) {
                            $inventory->quantity += $item->quantity;
                            $inventory->save();
                        }
                    }
                }
            } elseif ($request->status == 'returned') {
                 $order->status = 'returned';
                 $order->save();
                 // Tùy chọn: Thêm lại tồn kho vào kho đích nếu có
            } elseif ($request->status == 'in_transit') {
                $order->status = 'processing';
                $order->save();
            } else { // pending
                 $order->status = 'pending';
                 $order->save();
            }
        });

        return redirect()->route('shipments.index')->with('success', 'Chuyến hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shipment $shipment)
    {
        DB::transaction(function () use ($shipment) {
            // Hoàn lại tồn kho nếu chuyến hàng đang trong trạng thái 'in_transit' khi bị xóa
            if ($shipment->status == 'in_transit') {
                $order = $shipment->order;
                foreach ($order->orderItems as $item) {
                    $inventory = Inventory::where('warehouse_id', $shipment->origin_warehouse_id)
                                          ->where('product_id', $item->product_id)
                                          ->first();
                    if ($inventory) {
                        $inventory->quantity += $item->quantity;
                        $inventory->save();
                    }
                }
            }

            // Cập nhật trạng thái đơn hàng về lại 'pending' hoặc trạng thái phù hợp khác
            // (nếu đơn hàng không có chuyến hàng nào khác đang vận chuyển)
            $order = $shipment->order;
            $order->status = 'pending'; // Hoặc một logic phức tạp hơn
            $order->save();

            $shipment->delete();
        });

        return redirect()->route('shipments.index')->with('success', 'Chuyến hàng đã được xóa thành công!');
    }
}