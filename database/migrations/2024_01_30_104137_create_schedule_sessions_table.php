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
        Schema::create('schedule_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('plan_price')->default(0);
            $table->string('plan_hours');
            $table->string('unique_color');
            $table->string('blackout_dates')->nullable();
            $table->string('month_limit');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('duration');
            $table->json('days');
            $table->json('teachers');
            $table->longText('description')->nullable();
            $table->boolean('recommended')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
            // $table->foreignId('grade_id')->constrained('session_grades');
            // $table->string('customize_hour');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_sessions');
    }
};
