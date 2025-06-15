<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product; // Để lấy danh sách sản phẩm
use App\Models\Warehouse; // Để lấy danh sách kho hàng
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Hiển thị danh sách tồn kho theo sản phẩm hoặc kho hàng.
     */
    public function index(Request $request)
    {
        // Lấy tất cả tồn kho, có thể thêm filter sau này
        $query = Inventory::with('product', 'warehouse');

        // Ví dụ filter theo kho hàng hoặc sản phẩm
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $inventoryItems = $query->latest()->paginate(10); // Phân trang 10 mục mỗi trang

        $products = Product::orderBy('name')->get(); // Dùng cho dropdown filter
        $warehouses = Warehouse::orderBy('name')->get(); // Dùng cho dropdown filter

        return view('inventory.index', compact('inventoryItems', 'products', 'warehouses'));
    }

    /**
     * Hiển thị form để tạo một mục tồn kho mới.
     * Người dùng chọn sản phẩm và kho hàng.
     */
    public function create()
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('inventory.create', compact('products', 'warehouses'));
    }

    /**
     * Lưu một mục tồn kho mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0|sometimes|gt:min_stock_level', // max_stock_level phải lớn hơn min_stock_level
        ]);

        // Kiểm tra xem đã có bản ghi tồn kho cho cặp sản phẩm-kho này chưa
        $existingInventory = Inventory::where('product_id', $request->product_id)
                                    ->where('warehouse_id', $request->warehouse_id)
                                    ->first();

        if ($existingInventory) {
            return redirect()->back()->withInput()->withErrors(['product_id' => 'Sản phẩm này đã có tồn kho trong kho hàng đã chọn. Hãy chỉnh sửa thay vì tạo mới.']);
        }

        Inventory::create([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
            'quantity' => $request->quantity,
            'min_stock_level' => $request->min_stock_level ?? 0,
            'max_stock_level' => $request->max_stock_level,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Mục tồn kho đã được thêm thành công!');
    }

    /**
     * Hiển thị chi tiết một mục tồn kho.
     */
    public function show(Inventory $inventory)
    {
        // Route Model Binding sẽ tự động load Inventory và các quan hệ của nó
        $inventory->load('product', 'warehouse');
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Hiển thị form để chỉnh sửa một mục tồn kho.
     */
    public function edit(Inventory $inventory)
    {
        $products = Product::all();
        $warehouses = Warehouse::all();
        return view('inventory.edit', compact('inventory', 'products', 'warehouses'));
    }

    /**
     * Cập nhật một mục tồn kho trong cơ sở dữ liệu.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            // product_id và warehouse_id không được thay đổi khi cập nhật,
            // hoặc nếu có thì phải kiểm tra tính duy nhất lại
            'product_id' => 'required|exists:products,id', // Vẫn validate để đảm bảo ID hợp lệ
            'warehouse_id' => 'required|exists:warehouses,id', // Vẫn validate để đảm bảo ID hợp lệ
            'quantity' => 'required|integer|min:0',
            'min_stock_level' => 'nullable|integer|min:0',
            'max_stock_level' => 'nullable|integer|min:0|sometimes|gt:min_stock_level',
        ]);

        // Kiểm tra tính duy nhất nếu product_id hoặc warehouse_id thay đổi
        if ($request->product_id != $inventory->product_id || $request->warehouse_id != $inventory->warehouse_id) {
            $existingInventory = Inventory::where('product_id', $request->product_id)
                                        ->where('warehouse_id', $request->warehouse_id)
                                        ->where('id', '!=', $inventory->id) // Loại trừ chính bản ghi đang sửa
                                        ->first();
            if ($existingInventory) {
                return redirect()->back()->withInput()->withErrors(['product_id' => 'Sản phẩm này đã có tồn kho trong kho hàng đã chọn.']);
            }
        }

        $inventory->update([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
            'quantity' => $request->quantity,
            'min_stock_level' => $request->min_stock_level ?? 0,
            'max_stock_level' => $request->max_stock_level,
        ]);

        return redirect()->route('inventory.index')->with('success', 'Mục tồn kho đã được cập nhật thành công!');
    }

    /**
     * Xóa một mục tồn kho khỏi cơ sở dữ liệu.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return redirect()->route('inventory.index')->with('success', 'Mục tồn kho đã được xóa thành công!');
    }
}