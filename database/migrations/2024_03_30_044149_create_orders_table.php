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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('ingredients');
            $table->text('allergy_information')->nullable();
            $table->text('nutritional_information')->nullable();
            $table->string('dietary_restrictions')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_frozen')->default(false);
            $table->boolean('delivery_status')->default(false);
            $table->string('image')->nullable();
            $table->string('temperature');
            $table->boolean('is_preparing')->default(false);
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_pickup')->default(false);
            $table->boolean('is_delivered')->default(false);
            $table->foreignId('orderer_id')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
