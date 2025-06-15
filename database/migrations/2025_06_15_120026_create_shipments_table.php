<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_shipments_table.php

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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique(); // Mã vận đơn, duy nhất
            $table->foreignId('order_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng orders
            $table->foreignId('vehicle_id')->nullable()->constrained()->onDelete('set null'); // Phương tiện được gán
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null'); // Tài xế (liên kết với bảng users)
            $table->foreignId('origin_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouses')->onDelete('set null');
            $table->enum('status', [
                'pending',      // Chờ được gán xe/tài xế
                'assigned',     // Đã gán xe/tài xế
                'in_transit',   // Đang trên đường
                'out_for_delivery', // Đang đi giao hàng
                'delivered',    // Đã giao hàng
                'failed',       // Giao hàng thất bại
                'cancelled'     // Hủy vận chuyển
            ])->default('pending');
            $table->timestamp('scheduled_pickup_at')->nullable(); // Thời gian dự kiến lấy hàng
            $table->timestamp('actual_pickup_at')->nullable(); // Thời gian thực tế lấy hàng
            $table->timestamp('scheduled_delivery_at')->nullable(); // Thời gian dự kiến giao hàng
            $table->timestamp('actual_delivery_at')->nullable(); // Thời gian thực tế giao hàng
            $table->date('shipment_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('current_location_lat')->nullable(); // Vĩ độ vị trí hiện tại
            $table->string('current_location_long')->nullable(); // Kinh độ vị trí hiện tại
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};