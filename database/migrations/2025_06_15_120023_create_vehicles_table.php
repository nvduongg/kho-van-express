<?php

// database/migrations/YYYY_MM_DD_HHMMSS_create_vehicles_table.php

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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate')->unique(); // Biển số xe, duy nhất
            $table->string('make'); // Hãng xe (ví dụ: Ford, Toyota)
            $table->string('model')->nullable(); // Model xe (ví dụ: Transit, Hilux)
            $table->string('type')->nullable(); // Loại xe (ví dụ: Truck, Van, Motorcycle)
            $table->decimal('capacity_weight', 8, 2)->nullable(); // Sức chứa theo trọng lượng (ví dụ: tấn)
            $table->decimal('capacity_volume', 8, 2)->nullable(); // Sức chứa theo thể tích (ví dụ: m3)
            $table->string('status')->default('available'); // Trạng thái xe (available, in_use, maintenance)
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};