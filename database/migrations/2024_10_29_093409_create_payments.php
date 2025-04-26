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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('no action');
            $table->foreignId('walletID')->constrained('wallets')->onDelete('no action');
            $table->foreignId('adminID')->nullable()->constrained('admins')->onDelete('no action');
            $table->string('reference')->unique(); // Unique reference for each payment
            $table->string('gateway')->nullable(); // Payment gateway used, e.g., 'Paystack', 'Stripe'
            $table->string('channel')->nullable(); // Payment channel, e.g., 'card', 'bank transfer'
            $table->decimal('amount', 15, 2); // Payment amount
            $table->decimal('balanceBefore', 15, 2); // Balance before payment
            $table->decimal('balanceAfter', 15, 2); // Balance after payment
            $table->decimal('fees', 15, 2)->default(0.00); // Transaction fees if applicable
            $table->decimal('total', 15, 2); // Total amount including fees
            $table->string('status')->nullable(); // Payment status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
