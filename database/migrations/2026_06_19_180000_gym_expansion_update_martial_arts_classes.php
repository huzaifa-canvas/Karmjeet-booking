<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gym Expansion: Update martial_arts_classes table
     * - Add room/location for multi-gym scheduling
     * - Add is_tax_inclusive checkbox
     * - Remove stripe_security_price_id (security deposit removed)
     */
    public function up(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->string('room')->nullable()->after('instructor');
            $table->boolean('is_tax_inclusive')->default(false)->after('price');

            // Remove security deposit stripe field
            if (Schema::hasColumn('martial_arts_classes', 'stripe_security_price_id')) {
                $table->dropColumn('stripe_security_price_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            $table->dropColumn(['room', 'is_tax_inclusive']);
            $table->string('stripe_security_price_id')->nullable()->after('stripe_price_id');
        });
    }
};
