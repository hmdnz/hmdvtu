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
        Schema::create('users', function (Blueprint $table) {
            $table->id('id');
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('username')->unique();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('phone2')->nullable();
            $table->string('nin')->nullable()->unique();
            $table->string('bvn')->nullable()->unique();
            $table->string('state')->nullable();
            $table->string('lga')->nullable();
            $table->text('address')->nullable();
            $table->string('pin')->nullable();
            $table->string('password');
            $table->string('role')->nullable(); 
            $table->string('picture')->nullable();
            $table->string('isVerified')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
