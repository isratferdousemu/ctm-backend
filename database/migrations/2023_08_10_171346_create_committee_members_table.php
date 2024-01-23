<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('committee_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('committee_id');
            $table->foreign('committee_id')->references('id')->on('committees')->onDelete('cascade');
            $table->string('member_name', 255);
            $table->unsignedBigInteger('designation_id')->index()->nullable();
            $table->foreign('designation_id')->references('id')->on('lookups')->onDelete('cascade');
            $table->string('phone', 13);
            $table->string('email', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('committee_members');
    }
};
