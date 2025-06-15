<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Import Storage facade

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách các sản phẩm.
     */
    public function index()
    {
        $products = Product::all(); // Lấy tất cả sản phẩm
        return view('products.index', compact('products'));
    }

    /**
     * Hiển thị form để tạo sản phẩm mới.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Lưu sản phẩm mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:products',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Quy tắc cho file ảnh
            'is_active' => 'boolean',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public'); // Lưu vào storage/app/public/product_images
        }

        Product::create([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'unit' => $request->unit,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }

    /**
     * Hiển thị chi tiết một sản phẩm cụ thể.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Hiển thị form để chỉnh sửa sản phẩm.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Cập nhật sản phẩm trong cơ sở dữ liệu.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'length' => 'nullable|numeric|min:0',
            'width' => 'nullable|numeric|min:0',
            'height' => 'nullable|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'boolean',
        ]);

        $imagePath = $product->image_path; // Giữ nguyên ảnh cũ nếu không có ảnh mới
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('product_images', 'public');
        } elseif ($request->has('remove_image')) { // Thêm checkbox để xóa ảnh hiện tại
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }


        $product->update([
            'sku' => $request->sku,
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight,
            'length' => $request->length,
            'width' => $request->width,
            'height' => $request->height,
            'unit' => $request->unit,
            'price' => $request->price,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Xóa một sản phẩm khỏi cơ sở dữ liệu.
     */
    public function destroy(Product $product)
    {
        // Xóa ảnh liên quan nếu có
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công!');
    }
}