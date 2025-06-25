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
        Schema::create('itmaintenance', function (Blueprint $table) {
            $table->id();
            $table->string('month');
            $table->string('year');
            $table->string('assets_number');
            $table->string('condition')->nullable();
            $table->date('checking_date')->nullable();
            $table->string('checker')->nullable();
            $table->string('remark')->nullable();
            $table->string('void');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itmaintenance');
    }
};
