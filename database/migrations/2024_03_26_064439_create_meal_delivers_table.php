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
        Schema::create('meal_delivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->references('id')->on('partners')->constrained()->onDelete('cascade');
            $table->foreignId('volunteer_id')->references('id')->on('volunteers')->constrained()->onDelete('cascade');
            $table->date('delivery_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_delivers');
    }
};
