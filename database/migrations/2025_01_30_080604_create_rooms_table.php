<?php

use App\Enums\RoomStatusEnum;
use App\Enums\RoomTypeEnum;
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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', array_column(RoomTypeEnum::cases(), 'value'));
            $table->enum('status', array_column(RoomStatusEnum::cases(), 'value'))->default(RoomStatusEnum::Available->value);
            $table->integer('capacity');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->timestamps();
            $table->index('clinic_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
