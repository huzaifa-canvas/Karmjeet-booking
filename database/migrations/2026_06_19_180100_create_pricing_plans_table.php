<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gym Expansion: Pricing Plans for tiered membership
     * Each martial_arts_class can have multiple pricing tiers
     * (e.g., 2 Classes/Week $150, Unlimited $200)
     */
    public function up(): void
    {
        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('martial_arts_class_id')->constrained('martial_arts_classes')->cascadeOnDelete();
            $table->string('name'); // e.g. "2 Classes/Week", "Unlimited", "Drop-In"
            $table->decimal('price', 8, 2);
            $table->enum('interval', ['monthly', 'weekly', 'one-time'])->default('monthly');
            $table->integer('class_limit_per_week')->nullable(); // null = unlimited
            $table->boolean('is_tax_inclusive')->default(false);
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
