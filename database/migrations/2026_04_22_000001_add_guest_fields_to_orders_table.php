<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Make user_id nullable for guest orders
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Guest customer fields
            $table->string('guest_name')->nullable()->after('user_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone']);
        });
    }
};
