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
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->decimal('unlimited_price', 8, 2)->nullable()->after('price');
            $table->decimal('day_pass_price', 8, 2)->nullable()->after('unlimited_price');
            $table->decimal('weekly_pass_price', 8, 2)->nullable()->after('day_pass_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->dropColumn(['unlimited_price', 'day_pass_price', 'weekly_pass_price']);
        });
    }
};
