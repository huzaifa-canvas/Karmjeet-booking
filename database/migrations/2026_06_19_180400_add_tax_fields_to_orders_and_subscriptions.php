<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gym Expansion: Add GST/PST tax columns to orders and subscription_payments
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 8, 2)->nullable()->after('total_amount');
            $table->decimal('gst_amount', 8, 2)->nullable()->after('subtotal');
            $table->decimal('pst_amount', 8, 2)->nullable()->after('gst_amount');
            $table->decimal('tax_amount', 8, 2)->nullable()->after('pst_amount');
            $table->string('discount_coupon_code')->nullable()->after('tax_amount');
            $table->decimal('discount_amount', 8, 2)->nullable()->after('discount_coupon_code');
        });

        if (Schema::hasTable('subscription_payments')) {
            Schema::table('subscription_payments', function (Blueprint $table) {
                $table->decimal('gst_amount', 8, 2)->nullable()->after('amount');
                $table->decimal('pst_amount', 8, 2)->nullable()->after('gst_amount');
                $table->decimal('tax_amount', 8, 2)->nullable()->after('pst_amount');
            });
        }
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'gst_amount', 'pst_amount', 'tax_amount', 'discount_coupon_code', 'discount_amount']);
        });

        if (Schema::hasTable('subscription_payments')) {
            Schema::table('subscription_payments', function (Blueprint $table) {
                $table->dropColumn(['gst_amount', 'pst_amount', 'tax_amount']);
            });
        }
    }
};
