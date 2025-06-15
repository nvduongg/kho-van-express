<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_order_items_table.php

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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng orders
            $table->foreignId('product_id')->constrained()->onDelete('restrict'); // Khóa ngoại tới bảng products
            $table->integer('quantity'); // Số lượng sản phẩm trong đơn hàng
            $table->decimal('price', 10, 2); // Giá của sản phẩm tại thời điểm đặt hàng
            $table->timestamps();

            // Đảm bảo mỗi sản phẩm chỉ xuất hiện một lần trong mỗi đơn hàng
            $table->unique(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};