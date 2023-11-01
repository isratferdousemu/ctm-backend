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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('application_id', 50);
            $table->bigInteger('forward_committee_id')->unsigned()->index()->nullable();
            $table->foreign('forward_committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->string('remark',120)->nullable();
            $table->bigInteger('program_id')->unsigned()->index();
            $table->foreign('program_id')->references('id')->on('allowance_programs')->onDelete('cascade');
            $table->enum('verification_type', [1,2]); // 1=nid, 2=birth
            $table->string('verification_number', 16);
            $table->date('date_of_birth');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
