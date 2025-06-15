<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Hiển thị danh sách các phương tiện.
     */
    public function index()
    {
        $vehicles = Vehicle::all(); // Lấy tất cả phương tiện
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Hiển thị form để tạo phương tiện mới.
     */
    public function create()
    {
        // Các loại phương tiện có thể chọn
        $vehicleTypes = ['Truck', 'Van', 'Motorcycle', 'Car', 'Other'];
        // Các trạng thái phương tiện
        $vehicleStatuses = ['available', 'in_use', 'maintenance', 'retired'];
        return view('vehicles.create', compact('vehicleTypes', 'vehicleStatuses'));
    }

    /**
     * Lưu phương tiện mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'license_plate' => 'required|string|max:50|unique:vehicles',
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'capacity_weight' => 'nullable|numeric|min:0',
            'capacity_volume' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        Vehicle::create($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Phương tiện đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết một phương tiện cụ thể.
     */
    public function show(Vehicle $vehicle)
    {
        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Hiển thị form để chỉnh sửa phương tiện.
     */
    public function edit(Vehicle $vehicle)
    {
        $vehicleTypes = ['Truck', 'Van', 'Motorcycle', 'Car', 'Other'];
        $vehicleStatuses = ['available', 'in_use', 'maintenance', 'retired'];
        return view('vehicles.edit', compact('vehicle', 'vehicleTypes', 'vehicleStatuses'));
    }

    /**
     * Cập nhật phương tiện trong cơ sở dữ liệu.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $request->validate([
            'license_plate' => 'required|string|max:50|unique:vehicles,license_plate,' . $vehicle->id,
            'make' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'capacity_weight' => 'nullable|numeric|min:0',
            'capacity_volume' => 'nullable|numeric|min:0',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $vehicle->update($request->all());

        return redirect()->route('vehicles.index')->with('success', 'Phương tiện đã được cập nhật thành công!');
    }

    /**
     * Xóa một phương tiện khỏi cơ sở dữ liệu.
     */
    public function destroy(Vehicle $vehicle)
    {
        // TODO: Cân nhắc xử lý các ràng buộc với bảng shipments
        // Ví dụ: Không cho xóa nếu phương tiện đang được gán cho một chuyến hàng
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success', 'Phương tiện đã được xóa thành công!');
    }
}