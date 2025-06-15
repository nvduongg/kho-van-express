<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Hiển thị danh sách các khách hàng.
     */
    public function index()
    {
        $customers = Customer::all(); // Lấy tất cả khách hàng
        return view('customers.index', compact('customers'));
    }

    /**
     * Hiển thị form để tạo khách hàng mới.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Lưu khách hàng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'phone' => 'nullable|string|max:50|unique:customers',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết một khách hàng cụ thể.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Hiển thị form để chỉnh sửa khách hàng.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Cập nhật khách hàng trong cơ sở dữ liệu.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:50|unique:customers,phone,' . $customer->id,
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $customer->update($request->all());

        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được cập nhật thành công!');
    }

    /**
     * Xóa một khách hàng khỏi cơ sở dữ liệu.
     */
    public function destroy(Customer $customer)
    {
        // TODO: Cân nhắc xử lý các ràng buộc với bảng orders
        // Ví dụ: Không cho xóa nếu khách hàng có đơn hàng, hoặc xóa cascade
        // Với mục đích phát triển, chúng ta có thể tạm thời xóa trực tiếp.
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Khách hàng đã được xóa thành công!');
    }
}