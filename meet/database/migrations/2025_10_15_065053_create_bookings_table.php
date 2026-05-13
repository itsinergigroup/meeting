<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('meeting_room_id')->constrained()->onDelete('cascade');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->text('purpose')->nullable();
            $table->enum('status', ['active', 'cancelled'])->default('active');
            $table->timestamps();

            // Indexes
            $table->index('booking_date');
            $table->index('meeting_room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
