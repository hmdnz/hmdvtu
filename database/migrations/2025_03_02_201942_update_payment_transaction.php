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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('provider_reference')->nullable()->after('reference');
            $table->text('response')->nullable();
            $table->string('canceled_by')->nullable();
            $table->text('canceled_reason')->nullable();
        });
        
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('provider_reference')->nullable()->after('reference');
            $table->text('response')->nullable();
            $table->string('canceled_by')->nullable();
            $table->text('canceled_reason')->nullable();
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
