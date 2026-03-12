<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coachings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->foreignId('class_id')->constrained('sessionclasses');
            $table->string('title')->default('no title');
            $table->string('coach_bio')->nullable();
            $table->string('metting_link')->default('https://meet.google.com/');
            $table->string('slug');
            $table->string('coach_name')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->longText('blackout_dates')->nullable();
            $table->longText('description')->nullable();
            $table->string('image_id')->nullable();
            $table->integer('month_limit')->default(1);
            $table->string('price_per_session')->nullable();
            $table->string('session_limit')->default(1);
            $table->boolean('status')->default(1);
            $table->string('product_id')->nullable();
            $table->string('price_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coachings');
    }
};
