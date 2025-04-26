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
        Schema::create('cable_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('cascade'); // User associated with the cable customer
            $table->string('name'); // Name of the cable customer
            $table->string('smartcard')->unique(); // Unique smartcard number
            $table->string('biller'); // Biller associated with the cable service
            $table->text('address')->nullable(); // Address of the cable customer
            $table->string('status')->nullable(); // Status of the cable customer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cable_customer');
    }
};
