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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('member');
            $table->string('email')->unique();
            $table->string('user_name');
            $table->string('password');
            $table->string('confirm_password');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->integer('age');
            $table->string('phone_number');
            $table->string('emergency_contact_number');
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->string('dietary_restriction');
            $table->string('image')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};