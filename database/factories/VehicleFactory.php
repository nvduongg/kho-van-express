<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['Truck', 'Van', 'Motorcycle', 'Car'];
        $status = ['available', 'in_use', 'maintenance'];

        return [
            'license_plate' => $this->faker->unique()->regexify('[A-Z]{2}[0-9]{5}'), // VD: AB12345
            'make' => $this->faker->company(),
            'model' => $this->faker->word(),
            'type' => $this->faker->randomElement($types),
            'capacity_weight' => $this->faker->randomFloat(2, 0.5, 10), // 0.5 đến 10 tấn
            'capacity_volume' => $this->faker->randomFloat(2, 1, 50), // 1 đến 50 m3
            'status' => $this->faker->randomElement($status),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}