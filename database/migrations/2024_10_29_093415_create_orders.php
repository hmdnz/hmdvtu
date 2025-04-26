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
            $table->foreignId('userID')->constrained('users')->onDelete('no action');
            $table->foreignId('billerID')->constrained('billers')->onDelete('no action');
            $table->foreignId('packageID')->constrained('packages')->onDelete('no action');
            $table->string('service')->nullable();
            $table->string('reference')->unique(); // Unique reference for each order
            $table->decimal('price', 10, 2); // Price per item
            $table->integer('quantity')->default(1); // Quantity of items ordered
            $table->decimal('total', 10, 2); // Total cost (price * quantity)
            $table->string('beneficiary')->nullable(); // Beneficiary's name
            $table->string('sender')->nullable(); // Sender's name
            $table->text('message')->nullable(); // Optional message for the order
            $table->string('meterType')->nullable(); // Type of meter if applicable
            $table->string('meterNumber')->nullable(); // Meter number if applicable
            $table->string('meterName')->nullable(); // Meter name if applicable
            $table->string('responseAPI')->nullable(); // Stores full response data from API
            $table->string('responseMessage')->nullable(); // Message from API response
            $table->string('token')->nullable(); // Token if applicable
            $table->string('status')->nullable(); // Order status
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
