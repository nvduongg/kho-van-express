<?php

namespace Database\Factories;

use App\Models\Shipment;
use App\Models\Order;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon; // Thêm dòng này để sử dụng Carbon

class ShipmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shipment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = [
            'pending', 'assigned', 'in_transit', 'out_for_delivery',
            'delivered', 'failed', 'cancelled'
        ];

        $order = Order::inRandomOrder()->first() ?? Order::factory()->create();
        $vehicle = Vehicle::inRandomOrder()->first() ?? Vehicle::factory()->create();
        $driver = User::inRandomOrder()->first() ?? User::factory()->create();

        // Chuyển đổi đối tượng DateTime sang Carbon trước khi sử dụng các phương thức như addDays()
        $scheduledPickup = Carbon::parse($this->faker->dateTimeBetween('-3 months', '+1 month'));

        // actualPickup có thể null hoặc được tính toán từ scheduledPickup
        $actualPickup = $this->faker->optional(0.8)->dateTimeBetween($scheduledPickup, (clone $scheduledPickup)->addDays(1));
        // Nếu $actualPickup không null, chuyển đổi nó thành Carbon
        if ($actualPickup) {
            $actualPickup = Carbon::parse($actualPickup);
        }

        $scheduledDelivery = (clone $scheduledPickup)->addDays($this->faker->numberBetween(1, 7));

        // actualDelivery có thể null hoặc được tính toán từ scheduledDelivery
        $actualDelivery = $this->faker->optional(0.7)->dateTimeBetween($scheduledDelivery, (clone $scheduledDelivery)->addDays(2));
        // Nếu $actualDelivery không null, chuyển đổi nó thành Carbon
        if ($actualDelivery) {
            $actualDelivery = Carbon::parse($actualDelivery);
        }


        return [
            'tracking_number' => 'TRK-' . $this->faker->unique()->ean8(),
            'order_id' => $order->id,
            'vehicle_id' => $vehicle->id,
            'driver_id' => $driver->id,
            'status' => $this->faker->randomElement($statuses),
            'scheduled_pickup_at' => $scheduledPickup,
            'actual_pickup_at' => $actualPickup,
            'scheduled_delivery_at' => $scheduledDelivery,
            'actual_delivery_at' => $actualDelivery,
            'current_location_lat' => $this->faker->latitude(8, 11),
            'current_location_long' => $this->faker->longitude(102, 109),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}