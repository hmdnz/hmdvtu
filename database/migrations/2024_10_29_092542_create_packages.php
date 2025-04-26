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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adminID')->constrained('admins')->onDelete('no action');
            $table->foreignId('billerID')->constrained('billers')->onDelete('no action');
            $table->string('title');
            $table->string('service');
            $table->string('size')->nullable();  // Optional: can store values like 'small', 'medium', 'large', or specific sizes
            $table->integer('validity')->nullable(); // Optional: number of days or months
            $table->decimal('cost', 10, 2)->nullable();  // Cost to the provider
            $table->decimal('price', 10, 2); // Price to the end-user
            $table->string('type')->nullable(); // Package type, e.g., 'subscription', 'one-time'
            $table->string('planType')->nullable(); // Specific plan type, if applicable
            $table->string('planID')->nullable()->unique(); // Unique ID for the plan, if applicable
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
