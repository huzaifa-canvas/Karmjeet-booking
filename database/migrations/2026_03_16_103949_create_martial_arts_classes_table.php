<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('martial_arts_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category')->nullable(); // Kids, Youth, Teens, Adults, Women Only, Competition, Fitness/Conditioning, Open Training, Private
            $table->string('type')->nullable(); // Muay Thai, Brazilian Jiu-Jitsu, Grappling, MMA, Kickboxing, Fitness
            $table->string('level')->default('All Levels'); // Beginner, Intermediate, Advanced, All Levels
            $table->string('age_group')->nullable(); // 5-6, 7-9, 10-15, Adults, All Ages
            $table->string('format')->nullable(); // Gi, No-Gi, Striking, Grappling, Mixed
            $table->string('instructor')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('martial_arts_classes');
    }
};
