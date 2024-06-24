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
        Schema::create('allowance_program_emergency_allotment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('allowance_program_id')->constrained('allowance_programs')->onDelete('cascade')->name('fk_allowance_program');
            $table->foreignId('emergency_allotment_id')->constrained('emergency_allotments')->onDelete('cascade')->name('fk_emergency_allotment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_allotment_program');
    }
};
