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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('registration_type', ['adult', 'minor'])->default('adult');
            $table->string('minor_full_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->integer('age')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('how_heard')->nullable();
            $table->boolean('prior_experience')->default(false);
            $table->string('experience_details')->nullable();
            $table->json('goals')->nullable(); // Fitness, Self-Defense, etc.
            $table->json('physical_readiness')->nullable(); // Answers to readiness questions
            $table->boolean('consent_background_check')->default(false);
            $table->boolean('media_release_consent')->default(false);
            $table->boolean('non_compete_agreement')->default(false);
            $table->boolean('criminal_record_agreement')->default(false);
            $table->boolean('waiver_agreement')->default(false);
            $table->string('signature')->nullable();
            $table->date('form_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
