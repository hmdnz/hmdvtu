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
        //
        Schema::dropIfExists('referrals');
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->nullable()->constrained('users')->onDelete('no action'); // User being referred
            $table->string('referrer')->nullable(); // User who referred
            $table->decimal('commission', 10, 2)->default(0.00); // Commission earned from referral
            $table->string('status')->nullable(); // Status of referral
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
