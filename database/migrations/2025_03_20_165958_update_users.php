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
        Schema::table('users', function (Blueprint $table) {
            $table->string('verifiedName')->nullable()->after('username');
            $table->boolean('bvn_verified')->nullable()->after('bvn');
            $table->string('accountName')->nullable()->after('bvn_verified');
            $table->string('bankCode')->nullable()->after('accountName');
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
