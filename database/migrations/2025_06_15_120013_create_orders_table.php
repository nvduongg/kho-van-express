<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_orders_table.php

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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Mã đơn hàng, duy nhất
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null'); // Khách hàng đặt hàng
            $table->string('recipient_name'); // Tên người nhận
            $table->string('recipient_phone'); // Số điện thoại người nhận
            $table->string('recipient_address'); // Địa chỉ người nhận
            $table->string('recipient_city')->nullable();
            $table->string('recipient_state')->nullable();
            $table->string('recipient_zip_code')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_zip_code')->nullable();
            $table->decimal('total_amount', 10, 2); // Tổng giá trị đơn hàng
            $table->decimal('shipping_cost', 8, 2)->default(0); // Chi phí vận chuyển
            $table->enum('status', [
                'pending',      // Đang chờ xử lý
                'processing',   // Đang xử lý (đã nhận đơn)
                'packed',       // Đã đóng gói
                'shipped',      // Đang vận chuyển
                'delivered',    // Đã giao hàng
                'cancelled',    // Đã hủy
                'returned'      // Đã trả hàng
            ])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamp('ordered_at')->useCurrent(); // Thời gian đặt hàng
            $table->dateTime('order_date')->nullable(); // Ngày đặt hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};