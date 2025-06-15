<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có đủ khách hàng trước khi tạo đơn hàng
        // Customer::factory()->count(20)->create(); // Có thể bỏ qua nếu CustomerSeeder đã chạy trước

        Order::factory()->count(30)->create(); // Tạo 30 đơn hàng giả
    }
}