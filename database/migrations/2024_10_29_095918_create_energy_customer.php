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
        Schema::create('energy_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('no action'); // User associated with the energy customer
            $table->string('name'); // Name of the energy customer
            $table->string('meterNumber')->unique(); // Unique meter number
            $table->string('meterType'); // Type of the meter (e.g., 'digital', 'analog')
            $table->string('disco'); // Distribution company (DISCO) serving the customer
            $table->text('address')->nullable(); // Address of the customer
            $table->string('status')->nullable(); // Status of the energy customer
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('energy_customer');
    }
};
