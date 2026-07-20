<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->enum('type', ['Public', 'Private'])->default('Public'); // convert enum to string for better compatibility
            $table->unsignedTinyInteger('min_capacity');
            $table->unsignedTinyInteger('max_capacity');
            $table->string('location')->nullable();
            $table->enum('status', ['Available', 'Maintenance'])->default('Available'); // convert enum to string for better compatibility
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
