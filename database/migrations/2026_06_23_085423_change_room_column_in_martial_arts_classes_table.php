<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->text('room')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->string('room', 255)->nullable()->change();
        });
    }
};
