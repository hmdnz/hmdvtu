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
        Schema::create('request_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adminID')->constrained('admins')->onDelete('no action'); // Admin who created the category
            $table->string('title'); // Title of the request category
            $table->string('status')->nullable(); // Status of the category
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_categories');
    }
};
