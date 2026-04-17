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
            $table->string('stripe_product_id')->nullable()->after('price');
            $table->string('stripe_price_id')->nullable()->after('stripe_product_id');
            $table->string('stripe_security_price_id')->nullable()->after('stripe_price_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('martial_arts_classes', function (Blueprint $table) {
            //
        });
    }
};
