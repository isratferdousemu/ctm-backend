<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('division_id');
            $table->unsignedInteger('district_id');
            $table->unsignedInteger('thana_id');
            $table->string('username',50)->unique();
            $table->string('full_name',50)->nullable();
            $table->string('email',30)->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile',13)->unique()->nullable();
            $table->unsignedInteger('role_id');
            $table->unsignedInteger('office_id');
            $table->unsignedInteger('assign_location_id');
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->integer('user_type')->nullable(); // 1 -> superadmin, 2 -> staff
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
