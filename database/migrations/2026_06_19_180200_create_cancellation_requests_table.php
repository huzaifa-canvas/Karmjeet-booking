<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gym Expansion: Cancellation Request tracking
     * 60-day notice period with in-person meeting requirement
     */
    public function up(): void
    {
        Schema::create('cancellation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            $table->enum('status', ['pending', 'meeting_scheduled', 'completed', 'rejected'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->date('meeting_date')->nullable();
            $table->date('effective_cancellation_date')->nullable(); // 60 days from requested_at
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancellation_requests');
    }
};
