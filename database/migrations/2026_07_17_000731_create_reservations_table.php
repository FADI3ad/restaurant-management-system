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
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->integer('number_of_guests');
            $table->string('code')->unique();
            $table->time('start_time');
            $table->enum('duration', ['30', '60', '90', '120', '150', '180'])->default('60');
            $table->date('date');
            $table->enum('status', ['Confirmed', 'Arrived', 'Cancelled' , 'Completed' , 'No_Show'])->default('Confirmed');
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade'); // change constrained to foreignId for better compatibility
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
