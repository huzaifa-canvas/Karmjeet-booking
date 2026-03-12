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
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('guardian_name')->nullable()->after('minor_full_name');
            $table->string('occupation')->nullable()->after('age');
            $table->string('gender')->nullable()->after('occupation');
            $table->string('secondary_phone')->nullable()->after('phone_number');
            $table->string('physician_name')->nullable()->after('emergency_contact_phone');
            $table->string('physician_phone')->nullable()->after('physician_name');
            $table->text('medical_conditions')->nullable()->after('physical_readiness');
            $table->text('allergies')->nullable()->after('medical_conditions');
            $table->boolean('emergency_treatment_consent')->default(false)->after('waiver_agreement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn([
                'guardian_name',
                'occupation',
                'gender',
                'secondary_phone',
                'physician_name',
                'physician_phone',
                'medical_conditions',
                'allergies',
                'emergency_treatment_consent'
            ]);
        });
    }
};
