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
        Schema::create('user_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userID')->nullable()->constrained('users')->onDelete('no action'); // Assuming 'users' table contains admins as well
            $table->string('username')->nullable();
            $table->ipAddress('IPAddress');
            $table->string('status')->nullable(); // Adjust status as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logs');
    }
};
