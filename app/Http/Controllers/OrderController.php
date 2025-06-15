<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Để sử dụng Transaction
use Carbon\Carbon; // Để quản lý ngày tháng

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('customer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('order_date_from')) {
            $query->whereDate('order_date', '>=', $request->order_date_from);
        }
        if ($request->filled('order_date_to')) {
            $query->whereDate('order_date', '<=', $request->order_date_to);
        }

        $orders = $query->latest()->paginate(10); // Phân trang 10 đơn hàng mỗi trang

        $customers = Customer::orderBy('name')->get();
        $orderStatuses = [
            'pending', 'processing', 'shipped', 'delivered', 'cancelled'
        ];

        return view('orders.index', compact('orders', 'customers', 'orderStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        return view('orders.create', compact('customers', 'products', 'orderStatuses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_zip_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'product_ids' => 'required|array|min:1', // ít nhất 1 sản phẩm
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            // Tạo đơn hàng chính
            $order = Order::create([
                'customer_id' => $request->customer_id,
                'order_date' => Carbon::parse($request->order_date),
                'status' => $request->status,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip_code' => $request->shipping_zip_code,
                'notes' => $request->notes,
                'total_amount' => 0, // Sẽ cập nhật sau khi thêm các mục
            ]);

            $totalAmount = 0;
            foreach ($request->product_ids as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $request->quantities[$index];

                if (!$product) {
                    // Xử lý trường hợp sản phẩm không tồn tại (mặc dù đã validate exists)
                    // Hoặc ném ngoại lệ nếu cần validation chặt chẽ hơn ở đây
                    continue;
                }

                // Tạo Order Item
                $order->orderItems()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price, // Sử dụng giá hiện tại của sản phẩm
                ]);

                $totalAmount += $product->price * $quantity;

                // Tùy chọn: Trừ đi số lượng tồn kho (chúng ta sẽ xây dựng chi tiết hơn trong module Shipment)
                // Hiện tại, chỉ log hoặc bỏ qua để đơn giản.
                // $inventoryItem = Inventory::where('product_id', $productId)->where('warehouse_id', $order->warehouse_id)->first();
                // if ($inventoryItem) {
                //     $inventoryItem->quantity -= $quantity;
                //     $inventoryItem->save();
                // }
            }

            // Cập nhật tổng tiền của đơn hàng
            $order->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('customer', 'orderItems.product'); // Load quan hệ
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('orderItems.product');
        $customers = Customer::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $orderStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        return view('orders.edit', compact('order', 'customers', 'products', 'orderStatuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'order_date' => 'required|date',
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
            'shipping_address' => 'nullable|string|max:255',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_zip_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => 'exists:products,id',
            'quantities' => 'required|array|min:1',
            'quantities.*' => 'integer|min:1',
        ]);

        DB::transaction(function () use ($request, $order) {
            // Cập nhật thông tin chính của đơn hàng
            $order->update([
                'customer_id' => $request->customer_id,
                'order_date' => Carbon::parse($request->order_date),
                'status' => $request->status,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_zip_code' => $request->shipping_zip_code,
                'notes' => $request->notes,
            ]);

            // Xóa tất cả các OrderItem cũ và thêm lại cái mới
            // Cẩn thận với cách này nếu bạn cần theo dõi lịch sử thay đổi tồn kho chính xác
            // Một cách tốt hơn là so sánh và cập nhật, nhưng để đơn giản ta dùng xóa/tạo lại
            $order->orderItems()->delete();

            $totalAmount = 0;
            foreach ($request->product_ids as $index => $productId) {
                $product = Product::find($productId);
                $quantity = $request->quantities[$index];

                $order->orderItems()->create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price, // Sử dụng giá hiện tại của sản phẩm
                ]);

                $totalAmount += $product->price * $quantity;
            }

            // Cập nhật lại tổng tiền của đơn hàng
            $order->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Khi xóa Order, các OrderItem liên quan sẽ tự động bị xóa nhờ onDelete('cascade')
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Đơn hàng đã được xóa thành công!');
    }
}