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
        Schema::create('switches', function (Blueprint $table) {
            $table->id();
            $table->string('context_type'); // 'service', 'biller', or 'category'
            $table->unsignedBigInteger('context_id')->nullable(); // null for category
            $table->string('category_title')->nullable(); // used only when context_type is 'category'
            $table->foreignId('provider_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('switches');
    }
};
