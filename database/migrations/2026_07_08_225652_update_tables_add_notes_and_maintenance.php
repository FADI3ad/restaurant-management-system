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
        Schema::table('tables', function (Blueprint $table) {
            $table->text('notes')->nullable()->after('status');
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tables MODIFY COLUMN status ENUM('Available', 'Occupied', 'Reserved', 'Maintenance') DEFAULT 'Available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE tables MODIFY COLUMN status ENUM('Available', 'Occupied', 'Reserved') DEFAULT 'Available'");
    }
};
