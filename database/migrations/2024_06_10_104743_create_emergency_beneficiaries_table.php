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
        Schema::create('emergency_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('allowance_programs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('application_id')->nullable();
            $table->string('name_en');
            $table->string('name_bn');
            $table->string('mother_name_en');
            $table->string('mother_name_bn');
            $table->string('father_name_en');
            $table->string('father_name_bn');
            $table->string('spouse_name_en')->nullable();
            $table->string('spouse_name_bn')->nullable();
            $table->string('identification_mark')->nullable();
            $table->string('age');
            $table->date('date_of_birth')->nullable();
            $table->string('nationality');
            $table->foreignId('gender_id')->constrained('lookups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('education_status')->nullable();
            $table->string('profession')->nullable();
            $table->string('religion');
            $table->string('marital_status');
            $table->string('email')->nullable();
            $table->enum('verification_type', [0, 1, 2]); //0 = Unverified 1 = nid 2= birth registration no
            $table->string('verification_number')->nullable();
            $table->string('image')->nullable();
            $table->string('signature')->nullable();

            $table->foreignId('division_id')->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('district_id')->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('location_type')->nullable()->constrained('lookups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('city_corp_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('district_pourashava_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('upazila_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pourashava_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('thana_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('union_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('ward_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('post_code');
            $table->string('address');
            $table->string('mobile')->nullable();

            $table->foreignId('p_division_id')->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_district_id')->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_location_type')->nullable()->constrained('lookups')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_city_corp_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_district_pourashava_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_upazila_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_pourashava_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_thana_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_union_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('p_ward_id')->nullable()->constrained('locations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('p_post_code');
            $table->string('p_address');
            $table->string('p_mobile')->nullable();

            $table->string('nominee_en')->nullable();
            $table->string('nominee_bn')->nullable();
            $table->string('nominee_verification_number')->nullable();
            $table->string('nominee_address')->nullable();
            $table->string('nominee_image')->nullable();
            $table->string('nominee_signature')->nullable();
            $table->string('nominee_relation_with_beneficiary')->nullable();
            $table->string('nominee_nationality')->nullable();

            $table->string('account_name');
            $table->string('account_number');
            $table->string('account_owner');
            $table->enum('status', [1, 2, 3])->default(1); // 1=Active, 2=Inactive, 3=Waiting
            $table->integer('score')->default(0);
            $table->string('remarks')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emergency_beneficiaries');
    }
};
