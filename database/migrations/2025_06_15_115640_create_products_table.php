<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique(); // Mã sản phẩm (Stock Keeping Unit), duy nhất
            $table->string('name'); // Tên sản phẩm
            $table->text('description')->nullable();
            $table->decimal('weight', 8, 2)->nullable(); // Trọng lượng (ví dụ: kg)
            $table->decimal('length', 8, 2)->nullable(); // Chiều dài (ví dụ: cm)
            $table->decimal('width', 8, 2)->nullable(); // Chiều rộng (ví dụ: cm)
            $table->decimal('height', 8, 2)->nullable(); // Chiều cao (ví dụ: cm)
            $table->string('unit')->nullable(); // Đơn vị tính (ví dụ: cái, hộp, thùng)
            $table->decimal('price', 10, 2)->nullable(); // Giá bán (nếu có)
            $table->string('image_path')->nullable(); // Đường dẫn ảnh sản phẩm
            $table->boolean('is_active')->default(true); // Sản phẩm có đang hoạt động không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};