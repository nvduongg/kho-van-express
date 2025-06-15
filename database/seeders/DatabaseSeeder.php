<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo một người dùng admin mặc định để dễ đăng nhập
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Mật khẩu là 'password'
        ]);

        // Gọi các Seeder theo thứ tự phụ thuộc
        $this->call([
            CustomerSeeder::class,    // Khách hàng cần có trước đơn hàng
            WarehouseSeeder::class,   // Kho hàng cần có trước tồn kho
            ProductSeeder::class,     // Sản phẩm cần có trước tồn kho và chi tiết đơn hàng
            VehicleSeeder::class,     // Phương tiện cần có trước vận chuyển

            // Các seeder này phụ thuộc vào các seeder trên
            InventorySeeder::class,   // Tồn kho cần Product và Warehouse
            OrderSeeder::class,       // Đơn hàng cần Customer
            // OrderItemSeeder và ShipmentSeeder cần được gọi sau khi các Order đã được tạo.
            // Để đơn giản, chúng ta có thể gọi chúng ngay sau OrderSeeder.
        ]);

        // OrderItem và Shipment có thể được tạo sau khi Order đã tồn tại
        // Đảm bảo Product, Customer, Vehicle và User (driver) đã tồn tại.
        // Trong Factory của OrderItem và Shipment, chúng ta đã có logic tạo nếu chưa có.
        // Tuy nhiên, việc tạo riêng ở đây sẽ giúp đảm bảo số lượng lớn.
        $this->call([
            OrderItemSeeder::class,
            ShipmentSeeder::class,
        ]);
    }
}