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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->integer('number_of_guests');
            $table->string('reservation_code')->unique();
            $table->dateTime('reservation_start_time');
            $table->dateTime('reservation_end_time');
            $table->enum('status', ['Confirmed', 'Arrived', 'Cancelled' , 'Completed' , 'No_Show'])->default('Confirmed');
            $table->foreignId('table_id')->constrained()->onDelete('cascade'); // change constrained to foreignId for better compatibility
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
