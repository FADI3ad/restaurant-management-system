<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('duration');
            $table->decimal('discount_price', 8, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::create('offer_item', function (Blueprint $table) {
            $table->foreignId('offer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->primary(['offer_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offer_item');
        Schema::dropIfExists('offers');
    }
};
