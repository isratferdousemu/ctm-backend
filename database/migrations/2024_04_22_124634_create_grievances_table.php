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
        Schema::create('grievances', function (Blueprint $table) {
            $table->id();
            $table->string('application_id', 50);
            $table->bigInteger('forward_committee_id')->unsigned()->index()->nullable();
            $table->foreign('forward_committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->string('remark', 120)->nullable();
            $table->bigInteger('program_id')->unsigned()->index();
            $table->foreign('program_id')->references('id')->on('allowance_programs')->onDelete('cascade');
            $table->enum('verification_type', [1, 2]); // 1=nid, 2=birth
            $table->string('verification_number', 16);
            $table->integer('age');
            $table->date('date_of_birth');
            $table->string('name_en');
            $table->string('image');
            $table->bigInteger('gender_id')->unsigned()->index();
            $table->foreign('gender_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->bigInteger('current_location_id')->unsigned()->index();
            $table->foreign('current_location_id')->references('id')->on('locations')->onDelete('cascade');
            $table->string('current_post_code');
            $table->string('current_address');
            $table->string('mobile');
            $table->string('account_name');
            $table->string('email');
            $table->integer('status')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grievances');
    }
};
