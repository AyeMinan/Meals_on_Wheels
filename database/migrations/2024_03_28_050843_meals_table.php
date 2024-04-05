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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('ingredients');
            $table->text('allergy_information')->nullable();
            $table->text('nutritional_information')->nullable();
            $table->string('dietary_restrictions')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('is_frozen')->default(false);
            $table->string('delivery_status')->default(false);
            $table->string('image')->nullable();
            $table->string('temperature');
            $table->string('is_preparing')->default(false);
            $table->string('is_finished')->default(false);
            $table->string('is_pickup')->default(false);
            $table->string('is_delivered')->default(false);
            $table->foreignId('partner_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
