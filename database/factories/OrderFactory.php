<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'processing', 'packed', 'shipped', 'delivered', 'cancelled', 'returned'];
        $customer = Customer::factory()->create(); // Tạo một khách hàng mới cho mỗi đơn hàng

        return [
            'order_number' => 'ORD-' . $this->faker->unique()->randomNumber(8),
            'customer_id' => $customer->id,
            'recipient_name' => $this->faker->name(),
            'recipient_phone' => $this->faker->phoneNumber(),
            'recipient_address' => $this->faker->streetAddress(),
            'recipient_city' => $this->faker->city(),
            'recipient_state' => $this->faker->state(),
            'recipient_zip_code' => $this->faker->postcode(),
            'total_amount' => $this->faker->randomFloat(2, 50000, 5000000), // 50.000 đến 5.000.000 VND
            'shipping_cost' => $this->faker->randomElement([0, 15000, 30000, 50000]),
            'status' => $this->faker->randomElement($statuses),
            'notes' => $this->faker->optional(0.3)->sentence(),
            'ordered_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}