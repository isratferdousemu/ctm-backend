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
        Schema::create('application_poverty_values', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('application_id')->unsigned()->index();
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');

            $table->bigInteger('variable_id')->unsigned()->index();
            $table->foreign('variable_id')->references('id')->on('variables')->onDelete('cascade');

            $table->bigInteger('sub_variable_id')->unsigned()->index()->nullable();
            $table->foreign('sub_variable_id')->references('id')->on('variables')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_poverty_values');
    }
};