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
            $table->string('number')->unique();
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->unsignedTinyInteger('number_of_guests');
            $table->time('start_time');
            $table->unsignedSmallInteger('duration')->default(60);   
            $table->date('date');
            $table->text('notes')->nullable();
            $table->enum('status', ['Confirmed', 'Checked_In', 'Cancelled', 'Completed', 'No_Show'])->default('Confirmed');
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
