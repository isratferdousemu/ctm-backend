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
             Schema::table('beneficiaries', function (Blueprint $table) {
 
               $table->string('nominee_date_of_birth')->nullable();
                 $table->string('nationality')->change();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('beneficiaries', function (Blueprint $table) {
 
                 $table->dropColumn('nominee_date_of_birth');
    });
    }
};
