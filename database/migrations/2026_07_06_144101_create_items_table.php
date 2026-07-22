<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 8, 2);
            $table->string('image')->nullable();
            $table->string('description')->nullable();
            $table->unsignedInteger('display_order')->default(0);
            $table->boolean('status')->default(true);
            $table->foreignId('subcategory_id')->constrained()->cascadeOnDelete();
            $table->unique(['subcategory_id', 'name']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
