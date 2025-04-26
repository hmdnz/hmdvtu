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
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->constrained('users')->onDelete('no action'); // User owning the virtual account
            $table->foreignId('walletID')->constrained('wallets')->onDelete('no action'); // Associated wallet
            $table->string('accountName'); // Name of the account holder
            $table->string('accountNumber')->unique(); // Unique account number
            $table->string('accountBank'); // Bank name
            $table->string('provider'); // Provider of the virtual account service
            $table->string('status')->nullable(); // Status of the virtual account
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_accounts');
    }
};
