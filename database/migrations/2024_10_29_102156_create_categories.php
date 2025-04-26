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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adminID')->constrained('admins')->onDelete('no action');
            $table->string('service');
            $table->string('title');
            $table->string('mtn')->nullable();
            $table->string('airtel')->nullable();
            $table->string('mobile')->nullable();
            $table->string('glo')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
