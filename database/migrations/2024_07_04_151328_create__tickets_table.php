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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('departure');
            $table->string('destination');
            $table->string('flight_number');
            $table->string('seat_number');
            $table->decimal('price', 8, 2);
            $table->integer('spots');
            $table->timestamp('departure_time');
            $table->timestamp('arrival_time');
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->unique(['flight_number', 'seat_number']);
            $table->unique(['departure', 'destination']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
