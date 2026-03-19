<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Buang receipt_path dari bookings (resit ada dalam payments je)
            if (Schema::hasColumn('bookings', 'receipt_path')) {
                $table->dropColumn('receipt_path');
            }
        });
    }
    public function down(): void {}
};