<?php

namespace Database\Seeders;

use App\Models\Shipment;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có đủ Orders, Vehicles, Users (drivers) trước khi tạo Shipments
        // Order::factory()->count(30)->create(); // Có thể bỏ qua
        // Vehicle::factory()->count(10)->create(); // Có thể bỏ qua
        // User::factory()->count(5)->create(); // Tạo thêm user nếu cần, giả sử 1 số user là tài xế

        // Tạo Shipments cho mỗi Order đã có, hoặc một số Order ngẫu nhiên
        Order::all()->each(function ($order) {
            Shipment::factory()->create([
                'order_id' => $order->id,
                'vehicle_id' => Vehicle::inRandomOrder()->first()->id ?? null,
                'driver_id' => User::inRandomOrder()->first()->id ?? null,
            ]);
        });
    }
}