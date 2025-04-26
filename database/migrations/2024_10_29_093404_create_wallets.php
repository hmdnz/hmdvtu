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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('no action');
            $table->string('identifier')->unique()->nullable(); // Unique identifier for the wallet
            $table->decimal('mainBalance', 15, 2)->default(0.00); // Main balance in the wallet
            $table->decimal('referralBalance', 15, 2)->default(0.00); // Referral balance in the wallet
            $table->string('status')->nullable(); // Wallet status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
