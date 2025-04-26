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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('no action');
            $table->foreignId('walletID')->constrained('wallets')->onDelete('no action');
            $table->foreignId('orderID')->nullable()->constrained('orders')->onDelete('set null');
            $table->string('reference')->unique(); // Unique reference for each transaction
            $table->enum('type', ['credit', 'debit']); // Type of transaction
            $table->decimal('amount', 15, 2); // Transaction amount
            $table->decimal('balanceBefore', 15, 2); // Balance before transaction
            $table->decimal('balanceAfter', 15, 2); // Balance after transaction
            $table->text('note')->nullable(); // Optional note for transaction details
            $table->string('status')->nullable(); // Transaction status
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
