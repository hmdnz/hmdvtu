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
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('no action');
            $table->string('reference')->unique(); // Unique reference for each support request
            $table->string('phone'); // Contact phone number
            $table->string('type'); // Type of request, e.g., 'technical', 'billing'
            $table->string('category')->nullable(); // Category of the issue
            $table->string('service')->nullable(); // Related service if applicable
            $table->text('body'); // Description of the issue
            $table->text('feedback')->nullable(); // Feedback or response to the request
            $table->string('status')->nullable(); // Status of the support request
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
