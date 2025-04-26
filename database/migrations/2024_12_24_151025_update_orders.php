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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('userID')->nullable()->change();
            $table->foreignId('billerID')->nullable()->change();
            $table->foreignId('packageID')->nullable()->change();
            $table->string('reference')->nullable()->change(); // Unique reference for each order
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('userID')->nullable()->change();
            $table->foreignId('walletID')->nullable()->change();
            $table->foreignId('orderID')->nullable()->change();
            $table->string('reference')->nullable()->change(); // Unique reference for each order
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
