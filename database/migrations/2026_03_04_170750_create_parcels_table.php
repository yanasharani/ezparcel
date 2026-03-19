<?php

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
    Schema::create('parcels', function (Blueprint $table) {
        $table->id();
        $table->string('tracking_number')->unique();
        $table->string('recipient_name');
        $table->string('recipient_phone');
        $table->string('courier');
        $table->date('arrived_date');
        $table->time('arrived_time');
        $table->enum('status', ['arrived', 'booked', 'collected'])->default('arrived');
        $table->decimal('price', 8, 2)->default(1.00);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcels');
    }
};
