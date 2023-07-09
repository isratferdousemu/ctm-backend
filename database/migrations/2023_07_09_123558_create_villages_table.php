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
        Schema::create('villages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('division_id')->unsigned()->index();
            $table->bigInteger('district_id')->unsigned()->index();
            $table->bigInteger('thana_id')->unsigned()->index();
            $table->bigInteger('union_id')->unsigned()->index();
            $table->bigInteger('ward_id')->unsigned()->index();
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('thana_id')->references('id')->on('thanas')->onDelete('cascade');
            $table->foreign('union_id')->references('id')->on('unions')->onDelete('cascade');
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('cascade');
            $table->string('code',6);
            $table->string('name_en',50);
            $table->string('name_bn',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('villages');
    }
};
