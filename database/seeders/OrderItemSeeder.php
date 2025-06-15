<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker; // Import Faker Factory

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create(); // Khởi tạo Faker instance

        // Lấy tất cả các Orders và Products để sử dụng
        $orders = Order::all();
        $products = Product::all();

        // Đảm bảo có Orders và Products để tạo Order Items
        if ($orders->isEmpty() || $products->isEmpty()) {
            $this->command->info('Không đủ Orders hoặc Products để tạo Order Items.');
            return;
        }

        // Với mỗi Order, tạo một số lượng OrderItem ngẫu nhiên với các Product duy nhất
        $orders->each(function ($order) use ($products, $faker) {
            // Chọn ngẫu nhiên số lượng sản phẩm cho đơn hàng này (tối đa là số sản phẩm có sẵn)
            $numberOfItems = $faker->numberBetween(1, min(5, $products->count()));

            // Lấy ngẫu nhiên các sản phẩm duy nhất
            $selectedProducts = $products->random($numberOfItems);

            foreach ($selectedProducts as $product) {
                // Kiểm tra xem OrderItem với cặp order_id và product_id này đã tồn tại chưa
                // Điều này giúp tránh lỗi trùng lặp khi chạy seeder nhiều lần (nếu không dùng migrate:fresh)
                // Tuy nhiên, với migrate:fresh, lỗi xảy ra trong cùng 1 vòng lặp.
                // Logic chọn `selectedProducts` ngẫu nhiên và duy nhất đã giải quyết vấn đề chính.

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $faker->numberBetween(1, 5),
                    'price' => $product->price ?? $faker->randomFloat(2, 10000, 500000), // Sử dụng giá sản phẩm nếu có
                ]);
            }
        });
    }
}