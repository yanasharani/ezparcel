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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('recipient_name');
        $table->text('delivery_address')->nullable();
        $table->date('booking_date');
        $table->time('booking_time');
        $table->enum('method', ['pickup', 'delivery']);
        $table->decimal('total_amount', 8, 2);
        $table->enum('status', ['pending', 'on_the_way', 'done'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
