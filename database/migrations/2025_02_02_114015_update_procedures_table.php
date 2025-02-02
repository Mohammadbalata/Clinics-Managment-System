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
        Schema::table('procedures', function (Blueprint $table) {
            // Drop clinic_id column and its foreign key constraint
            $table->dropForeign(['clinic_id']);
            $table->dropColumn('clinic_id');

            // Add room_id and doctor_id columns
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('procedures', function (Blueprint $table) {
            $table->dropForeign(['room_id']);
            $table->dropColumn('room_id');

            $table->dropForeign(['doctor_id']);
            $table->dropColumn('doctor_id');

            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
        });
    }
};
