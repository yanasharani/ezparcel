<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambah late_fee dan late_since dalam parcels
        Schema::table('parcels', function (Blueprint $table) {
            $table->decimal('late_fee', 8, 2)->default(0)->after('price');
            $table->date('late_since')->nullable()->after('late_fee');
            $table->string('status')->default('registered')->change();
        });
    }
    public function down(): void
    {
        Schema::table('parcels', function (Blueprint $table) {
            $table->dropColumn(['late_fee', 'late_since']);
        });
    }
};