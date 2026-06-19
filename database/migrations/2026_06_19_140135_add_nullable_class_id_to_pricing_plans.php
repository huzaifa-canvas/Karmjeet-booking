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
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropForeign(['martial_arts_class_id']);
        });

        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('martial_arts_class_id')->nullable()->change();
            $table->foreign('martial_arts_class_id')->references('id')->on('martial_arts_classes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->dropForeign(['martial_arts_class_id']);
        });

        Schema::table('pricing_plans', function (Blueprint $table) {
            $table->unsignedBigInteger('martial_arts_class_id')->nullable(false)->change();
            $table->foreign('martial_arts_class_id')->references('id')->on('martial_arts_classes')->cascadeOnDelete();
        });
    }
};
