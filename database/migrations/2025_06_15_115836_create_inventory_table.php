<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_inventory_table.php

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
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng products
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng warehouses
            $table->integer('quantity'); // Số lượng tồn kho
            $table->integer('min_stock_level')->default(0); // Mức tồn kho tối thiểu
            $table->integer('max_stock_level')->nullable(); // Mức tồn kho tối đa
            $table->timestamps();

            // Đảm bảo mỗi sản phẩm chỉ có một bản ghi tồn kho duy nhất trong mỗi kho
            $table->unique(['product_id', 'warehouse_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};