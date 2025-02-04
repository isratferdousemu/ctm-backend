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
        Schema::table('payroll_verification_settings', function (Blueprint $table) {
            $table->dropForeign(['verification_type_id']);
            $table->dropColumn('verification_type_id');
            $table->enum('verification_type', ['verification_process', 'direct_approval'])->after('id')->default('direct_approval');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_verification_settings', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('updated_by');
            $table->dropColumn('verification_type');
            $table->foreignId('verification_type_id')->constrained('lookups')->cascadeOnUpdate()->cascadeOnDelete()->after('id');
        });
    }
};
