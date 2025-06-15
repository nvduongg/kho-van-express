<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Hiển thị danh sách các kho hàng.
     */
    public function index()
    {
        $warehouses = Warehouse::all(); // Lấy tất cả các kho hàng từ database
        return view('warehouses.index', compact('warehouses')); // Trả về view và truyền dữ liệu
    }

    /**
     * Hiển thị form để tạo kho hàng mới.
     */
    public function create()
    {
        return view('warehouses.create'); // Trả về view của form tạo mới
    }

    /**
     * Lưu kho hàng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        // 2. Tạo bản ghi kho hàng mới
        Warehouse::create([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'description' => $request->description,
            'is_active' => $request->has('is_active'), // Kiểm tra checkbox
        ]);

        // 3. Chuyển hướng về trang danh sách với thông báo thành công
        return redirect()->route('warehouses.index')->with('success', 'Kho hàng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết một kho hàng cụ thể.
     */
    public function show(Warehouse $warehouse)
    {
        // Laravel tự động tìm kho hàng dựa trên ID trong URL (Route Model Binding)
        return view('warehouses.show', compact('warehouse'));
    }

    /**
     * Hiển thị form để chỉnh sửa kho hàng.
     */
    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse'));
    }

    /**
     * Cập nhật kho hàng trong cơ sở dữ liệu.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id, // unique ngoại trừ chính nó
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Kho hàng đã được cập nhật thành công!');
    }

    /**
     * Xóa một kho hàng khỏi cơ sở dữ liệu.
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Kho hàng đã được xóa thành công!');
    }
}