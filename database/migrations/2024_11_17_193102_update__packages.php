<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['billerID']);

            // Modify the column to match the referenced column type
            $table->unsignedBigInteger('billerID')->nullable()->change();

            // Add the foreign key constraint back
            $table->foreign('billerID')->references('id')->on('billers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            // Drop the updated foreign key constraint
            $table->dropForeign(['billerID']);

            // Revert the column change
            $table->integer('billerID')->nullable(false)->change();

            // Add the original foreign key constraint
            $table->foreign('billerID')->references('id')->on('billers')->onDelete('cascade');
        });
    }
};
