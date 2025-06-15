<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có đủ Products và Warehouses trước khi tạo Inventory
        // Product::factory()->count(50)->create(); // Có thể bỏ qua
        // Warehouse::factory()->count(5)->create(); // Có thể bỏ qua

        // Tạo Inventory cho mỗi cặp Product-Warehouse có thể có
        $products = Product::all();
        $warehouses = Warehouse::all();

        foreach ($warehouses as $warehouse) {
            foreach ($products as $product) {
                // Tạo một số lượng tồn kho ngẫu nhiên cho mỗi sản phẩm trong mỗi kho
                // Có thể thêm điều kiện để không phải tất cả sản phẩm đều có ở mọi kho
                if (rand(0, 10) < 8) { // Khoảng 80% sản phẩm có trong kho
                    Inventory::factory()->create([
                        'product_id' => $product->id,
                        'warehouse_id' => $warehouse->id,
                        'quantity' => rand(10, 500),
                    ]);
                }
            }
        }
    }
}