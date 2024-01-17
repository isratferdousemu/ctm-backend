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
        Schema::table('applications', function (Blueprint $table) {

            $table->foreignId('location_type_id')->nullable()
                ->constrained('lookups')->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('division_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('district_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();


            $table->foreignId('district_pouroshova_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();


            $table->foreignId('city_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();



            $table->foreignId('thana_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('union_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('pouroshova_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();

            $table->foreignId('ward_id')->nullable()
                ->constrained('locations')
                ->cascadeOnUpdate()->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['location_type_id']);
            $table->dropForeign(['division_id']);
            $table->dropForeign(['district_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['district_pouroshova_id']);
            $table->dropForeign(['thana_id']);
            $table->dropForeign(['union_id']);
            $table->dropForeign(['pouroshova_id']);
            $table->dropForeign(['ward_id']);
            $table->dropColumn('location_type_id', 'division_id', 'district_id',
                'city_id', 'district_pouroshova_id', 'thana_id',
                'union_id', 'pouroshova_id', 'ward_id'
            );
        });
    }
};
