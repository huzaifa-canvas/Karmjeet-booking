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
            $table->boolean('show_drop_in_options')->default(true)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->dropColumn('show_drop_in_options');
        });
    }
};
