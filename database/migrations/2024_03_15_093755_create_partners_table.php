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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('partner');
            $table->string('email')->unique();
            $table->string('user_name');
            $table->string('password');
            $table->string('confirm_password');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('address');
            $table->string('phone_number');
            $table->string('shop_name');
            $table->text('shop_address');
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
        Schema::dropIfExists('partners');
    }
};
