<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sku' => $this->faker->unique()->ean13(), // Mã SKU duy nhất
            'name' => $this->faker->word() . ' ' . $this->faker->colorName() . ' ' . $this->faker->randomElement(['Shirt', 'Pants', 'Bag', 'Tool', 'Book', 'Gadget']),
            'description' => $this->faker->paragraph(2),
            'weight' => $this->faker->randomFloat(2, 0.1, 50), // 0.1kg đến 50kg
            'length' => $this->faker->randomFloat(2, 10, 200), // 10cm đến 200cm
            'width' => $this->faker->randomFloat(2, 10, 150),
            'height' => $this->faker->randomFloat(2, 5, 100),
            'unit' => $this->faker->randomElement(['cái', 'hộp', 'thùng', 'kg', 'mét']),
            'price' => $this->faker->randomFloat(2, 10000, 1000000), // 10.000 đến 1.000.000
            'image_path' => null, // Hoặc $this->faker->imageUrl() nếu bạn muốn ảnh thật
            'is_active' => $this->faker->boolean(95),
        ];
    }
}