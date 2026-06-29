<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_attributes', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['category', 'type', 'age_group', 'format', 'room'])->index();
            $table->string('name');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Add selected_location to subscriptions
        if (!Schema::hasColumn('subscriptions', 'selected_location')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->string('selected_location')->nullable()->after('package_type');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('class_attributes');

        if (Schema::hasColumn('subscriptions', 'selected_location')) {
            Schema::table('subscriptions', function (Blueprint $table) {
                $table->dropColumn('selected_location');
            });
        }
    }
};
