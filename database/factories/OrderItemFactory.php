<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Đảm bảo có Order và Product tồn tại
        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        $quantity = $this->faker->numberBetween(1, 5);
        $price = $product->price ?? $this->faker->randomFloat(2, 10000, 500000); // Lấy giá sản phẩm nếu có, không thì random

        return [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $price,
        ];
    }
}

DB::table('order_items')->truncate();

$usedPairs = [];

Order::all()->each(function ($order) use (&$usedPairs) {
    Product::inRandomOrder()->take(3)->get()->each(function ($product) use ($order, &$usedPairs) {
        $pairKey = $order->id . '-' . $product->id;
        if (!in_array($pairKey, $usedPairs)) {
            OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
            ]);
            $usedPairs[] = $pairKey;
        }
    });
});